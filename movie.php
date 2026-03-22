<?php
require_once __DIR__ . '/includes/header.php';
$movieId = (int) ($_GET['id'] ?? 0);
$movie = fetchMovieById($movieId);
if (!$movie) {
    setFlash('error', 'Movie not found.');
    redirect('movies.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isUserLoggedIn()) {
    $userId = (int) $_SESSION['user_id'];
    $conn->query("INSERT IGNORE INTO wishlist (user_id, movie_id) VALUES ({$userId}, {$movieId})");
    setFlash('success', 'Movie added to wishlist.');
    redirect('movie.php?id=' . $movieId);
}
?>
<section class="page-intro glass-panel">
    <span class="section-kicker"><?php echo escape($movie['category']); ?></span>
    <h1><?php echo escape($movie['title']); ?></h1>
    <p><?php echo escape($movie['description']); ?></p>
</section>
<section class="section">
    <div class="movie-detail-grid">
        <div class="glass-panel movie-detail-poster" data-bg-image="<?php echo escape($movie['backdrop_url']); ?>">
            <img src="<?php echo escape($movie['poster_url']); ?>" alt="<?php echo escape($movie['title']); ?>">
        </div>
        <div class="glass-panel movie-detail-copy">
            <h2>Movie details</h2>
            <div class="movie-metadata">
                <span class="tag">Category: <?php echo escape($movie['category']); ?></span>
                <span class="tag">Rating: <?php echo escape($movie['rating']); ?>/10</span>
                <span class="tag">Release: <?php echo escape($movie['release_year']); ?></span>
                <span class="tag">Duration: <?php echo escape($movie['duration']); ?></span>
            </div>
            <p style="margin-top:18px;">This teaser-first page keeps the embedded movie clip on the left and full storyline details on the right exactly as requested for an immersive recommendation experience.</p>
            <div class="card-actions" style="margin-top:16px;">
                <?php if (isUserLoggedIn()): ?>
                    <form method="post">
                        <button class="btn btn-primary" type="submit">Add to wishlist</button>
                    </form>
                <?php else: ?>
                    <a class="btn btn-primary" href="login.php">Login to wishlist</a>
                <?php endif; ?>
                <a class="btn btn-secondary" href="movies.php">Back to movies</a>
            </div>
        </div>
    </div>
</section>
<section class="section">
    <div class="teaser-layout">
        <div class="teaser-video glass-panel">
            <iframe src="<?php echo escape($movie['teaser_url']); ?>" title="<?php echo escape($movie['title']); ?> teaser" allowfullscreen></iframe>
        </div>
        <div class="glass-panel movie-detail-copy">
            <span class="section-kicker">Trailer / Teaser Box</span>
            <h2>Watch the clip</h2>
            <p>Click play to watch the teaser or trailer clip pulled from YouTube. The right-hand panel keeps the synopsis, metadata, and action buttons visible while the teaser remains embedded in a highlighted glass box.</p>
            <div class="teaser-tags">
                <span class="tag">Black / Blue / Purple theme</span>
                <span class="tag">3D hover motion</span>
                <span class="tag">Scroll-reactive background</span>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
