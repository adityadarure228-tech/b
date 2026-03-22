<?php
require_once __DIR__ . '/includes/header.php';
if (!isUserLoggedIn()) {
    setFlash('error', 'Please login to view your wishlist.');
    redirect('login.php');
}
$userId = (int) $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_wishlist_id'])) {
    $wishlistId = (int) $_POST['remove_wishlist_id'];
    $conn->query("DELETE FROM wishlist WHERE id = {$wishlistId} AND user_id = {$userId}");
    setFlash('success', 'Wishlist item removed.');
    redirect('wishlist.php');
}
$wishlistMovies = fetchWishlistMovies($userId);
?>
<section class="page-intro glass-panel">
    <span class="section-kicker">Wishlist</span>
    <h1>Your saved movie cards</h1>
    <p>Keep your personal watchlist of trailers, teasers, and favorite recommendations in one place.</p>
</section>
<section class="section">
    <?php if (!$wishlistMovies): ?>
        <div class="empty-state glass-panel">
            <h2>No wishlist items yet</h2>
            <p>Start browsing the movie collection and add your favorite titles.</p>
            <a class="btn btn-primary" href="movies.php">Browse movies</a>
        </div>
    <?php else: ?>
        <div class="movies-grid">
            <?php foreach ($wishlistMovies as $movie): ?>
                <article class="movie-card" data-category="<?php echo escape($movie['category']); ?>" data-bg-image="<?php echo escape($movie['backdrop_url']); ?>">
                    <div class="movie-poster">
                        <img src="<?php echo escape($movie['poster_url']); ?>" alt="<?php echo escape($movie['title']); ?>">
                        <div class="movie-overlay"><span class="tag">Wishlist</span></div>
                    </div>
                    <div class="movie-body">
                        <h3><?php echo escape($movie['title']); ?></h3>
                        <p><?php echo escape($movie['description']); ?></p>
                        <div class="card-actions">
                            <a class="btn btn-primary" href="movie.php?id=<?php echo (int) $movie['id']; ?>">Watch teaser</a>
                            <form method="post">
                                <input type="hidden" name="remove_wishlist_id" value="<?php echo (int) $movie['wishlist_id']; ?>">
                                <button class="btn btn-secondary" type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
