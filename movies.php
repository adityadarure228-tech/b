<?php
require_once __DIR__ . '/includes/header.php';
$selectedCategory = $_GET['category'] ?? null;
$movies = fetchAllMovies($selectedCategory ?: null);
$categories = fetchCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isUserLoggedIn()) {
    $movieId = (int) ($_POST['movie_id'] ?? 0);
    if ($movieId > 0) {
        $userId = (int) $_SESSION['user_id'];
        $conn->query("INSERT IGNORE INTO wishlist (user_id, movie_id) VALUES ({$userId}, {$movieId})");
        setFlash('success', 'Movie added to wishlist.');
        redirect('movies.php' . ($selectedCategory ? '?category=' . urlencode($selectedCategory) : ''));
    }
}
?>
<section class="page-intro glass-panel">
    <span class="section-kicker">Movies Library</span>
    <h1>Animated movie cards and teaser previews</h1>
    <p>Browse every movie recommendation with floating 3D cards, category filters, scroll-changing poster glows, and teaser previews when you hover or click a movie.</p>
</section>

<section class="section">
    <div class="section-header">
        <div>
            <h2>Genres</h2>
            <p>Use the filter buttons to focus on horror, thriller, romantic, sci-fi, and other categories.</p>
        </div>
        <div class="filter-row">
            <a class="filter-btn <?php echo !$selectedCategory ? 'btn-primary' : ''; ?>" href="movies.php">All</a>
            <?php foreach ($categories as $category): ?>
                <a class="filter-btn <?php echo $selectedCategory === $category['name'] ? 'btn-primary' : ''; ?>" href="movies.php?category=<?php echo urlencode($category['name']); ?>"><?php echo escape($category['name']); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="movies-grid">
        <?php foreach ($movies as $movie): ?>
            <article class="movie-card" data-category="<?php echo escape($movie['category']); ?>" data-bg-image="<?php echo escape($movie['backdrop_url']); ?>">
                <div class="movie-poster">
                    <img src="<?php echo escape($movie['poster_url']); ?>" alt="<?php echo escape($movie['title']); ?>">
                    <div class="hover-teaser">
                        <iframe src="<?php echo escape($movie['teaser_url']); ?>&mute=1" title="<?php echo escape($movie['title']); ?> teaser preview" allowfullscreen></iframe>
                    </div>
                    <div class="movie-overlay">
                        <span class="tag"><?php echo escape($movie['category']); ?></span>
                    </div>
                </div>
                <div class="movie-body">
                    <h3><?php echo escape($movie['title']); ?></h3>
                    <p><?php echo escape($movie['description']); ?></p>
                    <div class="meta-pill-row">
                        <span class="meta-pill">⭐ <?php echo escape($movie['rating']); ?></span>
                        <span class="meta-pill">📅 <?php echo escape($movie['release_year']); ?></span>
                        <span class="meta-pill">⏱ <?php echo escape($movie['duration']); ?></span>
                    </div>
                    <div class="card-actions" style="margin-top:16px;">
                        <a class="btn btn-primary" href="movie.php?id=<?php echo (int) $movie['id']; ?>">Details</a>
                        <?php if (isUserLoggedIn()): ?>
                            <form method="post">
                                <input type="hidden" name="movie_id" value="<?php echo (int) $movie['id']; ?>">
                                <button class="btn btn-secondary" type="submit">Add to wishlist</button>
                            </form>
                        <?php else: ?>
                            <a class="btn btn-secondary" href="login.php">Login to save</a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
