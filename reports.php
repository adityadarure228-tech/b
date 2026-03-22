<?php
require_once __DIR__ . '/includes/header.php';
$users = latestUsers(12);
$movies = latestMovies(12);
$categories = topCategories();
?>
<section class="page-intro glass-panel">
    <span class="section-kicker">Reports</span>
    <h1>User details, movies, and activity reports</h1>
    <p>This report area covers user details, latest movies, category distribution, and wishlist growth for the movie recommendation system.</p>
</section>
<section class="section">
    <div class="report-grid">
        <article class="report-card"><strong data-counter="<?php echo countTable('users'); ?>">0</strong><h2>Registered Users</h2><p>Total users who signed up with name, username, email, and password.</p></article>
        <article class="report-card"><strong data-counter="<?php echo countTable('movies'); ?>">0</strong><h2>Total Movies</h2><p>Available movie cards with online images and YouTube teaser embeds.</p></article>
        <article class="report-card"><strong data-counter="<?php echo totalWishlistEntries(); ?>">0</strong><h2>Total Wishlist Entries</h2><p>How many times users have saved titles into their personal wishlist.</p></article>
        <article class="report-card"><strong><?php echo escape(averageMovieRating()); ?></strong><h2>Average Rating</h2><p>Average rating of the current movie database.</p></article>
    </div>
</section>
<section class="section admin-grid">
    <div class="table-card">
        <h2>Latest user details</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Joined</th></tr></thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo (int) $user['id']; ?></td>
                            <td><?php echo escape($user['name']); ?></td>
                            <td><?php echo escape($user['username']); ?></td>
                            <td><?php echo escape($user['email']); ?></td>
                            <td><?php echo escape($user['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="table-card">
        <h2>Latest movie additions</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Title</th><th>Category</th><th>Rating</th><th>Year</th></tr></thead>
                <tbody>
                    <?php foreach ($movies as $movie): ?>
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
    </div>
</section>
<section class="section">
    <div class="table-card">
        <h2>Category summary</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Category</th><th>Movies</th><th>Insight</th></tr></thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo escape($category['category']); ?></td>
                            <td><?php echo escape($category['total']); ?></td>
                            <td><?php echo escape($category['category']); ?> keeps the animated recommendation grid balanced with teaser-first discovery and category filtering.</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
