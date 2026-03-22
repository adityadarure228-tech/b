<?php
require_once __DIR__ . '/includes/header.php';
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    setFlash('success', 'Admin logged out.');
    redirect('admin.php');
}
if (!isAdminLoggedIn() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($email === $adminEmail && $password === $adminPassword) {
        $_SESSION['admin_logged_in'] = true;
        setFlash('success', 'Admin login successful.');
        redirect('admin.php');
    }
    setFlash('error', 'Invalid admin credentials.');
    redirect('admin.php');
}

if (isAdminLoggedIn() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_movie'])) {
    $title = $conn->real_escape_string(trim($_POST['title'] ?? ''));
    $category = $conn->real_escape_string(trim($_POST['category'] ?? ''));
    $description = $conn->real_escape_string(trim($_POST['description'] ?? ''));
    $posterUrl = $conn->real_escape_string(trim($_POST['poster_url'] ?? ''));
    $backdropUrl = $conn->real_escape_string(trim($_POST['backdrop_url'] ?? ''));
    $teaserUrl = $conn->real_escape_string(trim($_POST['teaser_url'] ?? ''));
    $releaseYear = (int) ($_POST['release_year'] ?? date('Y'));
    $rating = (float) ($_POST['rating'] ?? 0);
    $duration = $conn->real_escape_string(trim($_POST['duration'] ?? ''));

    if ($title && $category && $description && $posterUrl && $backdropUrl && $teaserUrl && $duration) {
        $conn->query("INSERT INTO movies (title, category, description, poster_url, backdrop_url, teaser_url, release_year, rating, duration) VALUES ('{$title}', '{$category}', '{$description}', '{$posterUrl}', '{$backdropUrl}', '{$teaserUrl}', {$releaseYear}, {$rating}, '{$duration}')");
        $image = $conn->real_escape_string($backdropUrl);
        $catDesc = $conn->real_escape_string($category . ' titles curated by admin.');
        $conn->query("INSERT IGNORE INTO categories (name, image_url, description) VALUES ('{$category}', '{$image}', '{$catDesc}')");
        setFlash('success', 'Movie added successfully by admin.');
        redirect('admin.php');
    }
    setFlash('error', 'Please fill all movie details.');
    redirect('admin.php');
}
$categories = fetchCategories();
?>
<section class="page-intro glass-panel">
    <span class="section-kicker">Admin Panel</span>
    <h1>Manage movies, users, and reports</h1>
    <p>Use the fixed admin account to add movie cards, manage categories, and inspect user plus wishlist growth.</p>
</section>
<?php if (!isAdminLoggedIn()): ?>
    <section class="section auth-grid">
        <div class="form-card">
            <h2>Admin Login</h2>
            <p>Login with the requested fixed credentials.</p>
            <form method="post" class="form-grid">
                <input type="hidden" name="admin_login" value="1">
                <div class="form-field full">
                    <label for="email">Admin Email</label>
                    <input id="email" name="email" type="email" value="adi@gmail.com" required>
                </div>
                <div class="form-field full">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" value="123" required>
                </div>
                <div class="form-field full">
                    <button class="btn btn-primary" type="submit">Login as admin</button>
                </div>
            </form>
        </div>
        <div class="form-card">
            <h2>Fixed Credentials</h2>
            <div class="report-tags">
                <span class="tag badge-admin">adi@gmail.com</span>
                <span class="tag badge-admin">123</span>
            </div>
            <p style="margin-top:16px;">After logging in, the admin can add new movies with online poster images, backdrops, and YouTube teaser links.</p>
        </div>
    </section>
<?php else: ?>
    <section class="section">
        <div class="dashboard-grid">
            <article class="dashboard-card"><strong data-counter="<?php echo countTable('movies'); ?>">0</strong><h2>Movies</h2><p>Total movie cards inside the system.</p></article>
            <article class="dashboard-card"><strong data-counter="<?php echo countTable('users'); ?>">0</strong><h2>Users</h2><p>Registered viewers with username, email, and password-based login.</p></article>
            <article class="dashboard-card"><strong data-counter="<?php echo totalWishlistEntries(); ?>">0</strong><h2>Wishlist Saves</h2><p>Movies saved by users to their watchlists.</p></article>
            <article class="dashboard-card"><strong><?php echo escape(averageMovieRating()); ?></strong><h2>Average Rating</h2><p>Live average of available movie ratings.</p></article>
        </div>
    </section>
    <section class="section admin-grid">
        <div class="form-card">
            <h2>Add Movie</h2>
            <p>Create a new movie card with poster, backdrop, teaser embed URL, and metadata.</p>
            <form method="post" class="form-grid">
                <input type="hidden" name="add_movie" value="1">
                <div class="form-field"><label for="title">Title</label><input id="title" name="title" type="text" required></div>
                <div class="form-field"><label for="category">Category</label><input id="category" name="category" list="category-list" type="text" required></div>
                <div class="form-field full"><label for="description">Description</label><textarea id="description" name="description" required></textarea></div>
                <div class="form-field"><label for="poster_url">Poster URL</label><input id="poster_url" name="poster_url" type="url" required></div>
                <div class="form-field"><label for="backdrop_url">Backdrop URL</label><input id="backdrop_url" name="backdrop_url" type="url" required></div>
                <div class="form-field full"><label for="teaser_url">YouTube Embed URL</label><input id="teaser_url" name="teaser_url" type="url" placeholder="https://www.youtube.com/embed/..." required></div>
                <div class="form-field"><label for="release_year">Release Year</label><input id="release_year" name="release_year" type="number" min="1900" max="2100" required></div>
                <div class="form-field"><label for="rating">Rating</label><input id="rating" name="rating" type="number" min="0" max="10" step="0.1" required></div>
                <div class="form-field full"><label for="duration">Duration</label><input id="duration" name="duration" type="text" placeholder="2h 06m" required></div>
                <div class="form-field full"><button class="btn btn-primary" type="submit">Add movie</button></div>
                <datalist id="category-list">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo escape($category['name']); ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </form>
        </div>
        <div class="table-card">
            <h2>Latest Movies</h2>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Title</th><th>Category</th><th>Rating</th><th>Year</th></tr></thead>
                    <tbody>
                        <?php foreach (latestMovies(8) as $movie): ?>
                            <tr>
                                <td><?php echo escape($movie['title']); ?></td>
                                <td><?php echo escape($movie['category']); ?></td>
                                <td><?php echo escape($movie['rating']); ?></td>
                                <td><?php echo escape($movie['release_year']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="inline-actions" style="margin-top:16px;">
                <a class="btn btn-secondary" href="reports.php">Open reports</a>
                <a class="btn btn-primary" href="admin.php?logout=1">Logout admin</a>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
