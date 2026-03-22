<?php
require_once __DIR__ . '/includes/header.php';
$movies = fetchAllMovies();
$categories = fetchCategories();
$featured = featuredMovie();
?>
<section class="hero">
    <div class="floating-rings" data-rings></div>
    <div class="floating-dots" data-dots></div>
    <div class="hero-grid">
        <div class="glass-panel hero-copy">
            <span class="section-kicker">AI-Inspired Discovery Experience</span>
            <h1>Movie Recommendation System</h1>
            <p>Discover bold cinema across horror, thriller, romantic, sci-fi, fantasy, action, and more with immersive 3D cards, cinematic teaser boxes, scroll-reactive backgrounds, and a built-in wishlist.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="movies.php">Explore Movies</a>
                <a class="btn btn-secondary" href="register.php">Create Account</a>
                <a class="btn btn-secondary" href="admin.php">Admin Login</a>
            </div>
            <div class="hero-stats">
                <article class="stat-card">
                    <strong data-counter="<?php echo count($movies); ?>">0</strong>
                    <span>Curated movies with teaser links</span>
                </article>
                <article class="stat-card">
                    <strong data-counter="<?php echo count($categories); ?>">0</strong>
                    <span>Genre buttons and dynamic filters</span>
                </article>
                <article class="stat-card">
                    <strong data-counter="<?php echo totalWishlistEntries(); ?>">0</strong>
                    <span>Wishlist saves from registered viewers</span>
                </article>
            </div>
        </div>
        <div class="glass-panel hero-gallery">
            <?php foreach (array_slice($movies, 0, 4) as $index => $movie): ?>
                <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-hero-slide>
                    <img src="<?php echo escape($movie['backdrop_url']); ?>" alt="<?php echo escape($movie['title']); ?>">
                    <div class="hero-slide-copy">
                        <span class="tag"><?php echo escape($movie['category']); ?></span>
                        <h2><?php echo escape($movie['title']); ?></h2>
                        <p><?php echo escape($movie['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-header">
        <div>
            <span class="section-kicker">Category Buttons</span>
            <h2>Pick your vibe</h2>
            <p>Quick jump into horror, thriller, romantic, sci-fi, and more categories with animated buttons and artwork from online image sources.</p>
        </div>
        <div class="filter-row">
            <button class="filter-btn btn-primary" data-filter="all">All</button>
            <?php foreach ($categories as $category): ?>
                <button class="filter-btn" data-filter="<?php echo escape($category['name']); ?>"><?php echo escape($category['name']); ?></button>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="category-list">
        <?php foreach ($categories as $category): ?>
            <article class="category-card" style="background-image:url('<?php echo escape($category['image_url']); ?>');">
                <div>
                    <span class="tag"><?php echo escape($category['name']); ?></span>
                    <h2><?php echo escape($category['name']); ?></h2>
                    <p><?php echo escape($category['description']); ?></p>
                </div>
                <a class="btn btn-secondary" href="movies.php?category=<?php echo urlencode($category['name']); ?>">See movies</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="section-header">
        <div>
            <span class="section-kicker">Featured teaser section</span>
            <h2>Teaser in the box, details on the right</h2>
            <p>Each featured release highlights a trailer-style embed box with story, category, duration, rating, and release details aligned on the right side.</p>
        </div>
    </div>
    <?php if ($featured): ?>
        <div class="teaser-layout">
            <div class="teaser-video glass-panel" id="featured-teaser">
                <iframe src="<?php echo escape($featured['teaser_url']); ?>" title="Featured teaser" allowfullscreen></iframe>
            </div>
            <div class="glass-panel movie-detail-copy">
                <span class="section-kicker">Now spotlighting</span>
                <h2><?php echo escape($featured['title']); ?></h2>
                <p><?php echo escape($featured['description']); ?></p>
                <div class="movie-metadata">
                    <span class="tag"><?php echo escape($featured['category']); ?></span>
                    <span class="tag">⭐ <?php echo escape($featured['rating']); ?>/10</span>
                    <span class="tag">📅 <?php echo escape($featured['release_year']); ?></span>
                    <span class="tag">⏱ <?php echo escape($featured['duration']); ?></span>
                </div>
                <div class="hero-actions" style="margin-top:18px;">
                    <a class="btn btn-primary" href="movie.php?id=<?php echo (int) $featured['id']; ?>">Open movie details</a>
                    <a class="btn btn-secondary" href="wishlist.php">Go to wishlist</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<section class="section">
    <div class="section-header">
        <div>
            <span class="section-kicker">Movies card showcase</span>
            <h2>Hover cards with clip preview</h2>
            <p>Hover any movie card to reveal a teaser clip preview overlay, then click through for details and full teaser playback.</p>
        </div>
        <a class="btn btn-primary" href="movies.php">Open full library</a>
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
                        <a class="btn btn-primary" href="movie.php?id=<?php echo (int) $movie['id']; ?>">Watch teaser</a>
                        <button class="btn btn-secondary" data-teaser-target="#featured-teaser">Right side teaser</button>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
