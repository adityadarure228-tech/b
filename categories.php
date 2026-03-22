<?php
require_once __DIR__ . '/includes/header.php';
$categories = fetchCategories();
$topCategories = topCategories();
?>
<section class="page-intro glass-panel">
    <span class="section-kicker">Genre navigator</span>
    <h1>Category buttons for every mood</h1>
    <p>The system ships with horror, thriller, romantic, sci-fi, fantasy, action, mystery, and more genre buttons so visitors can jump directly into themed recommendations.</p>
</section>
<section class="section">
    <div class="category-list">
        <?php foreach ($categories as $category): ?>
            <article class="category-card" style="background-image:url('<?php echo escape($category['image_url']); ?>');">
                <div>
                    <span class="tag">Genre</span>
                    <h2><?php echo escape($category['name']); ?></h2>
                    <p><?php echo escape($category['description']); ?></p>
                </div>
                <div class="inline-actions">
                    <a class="btn btn-primary" href="movies.php?category=<?php echo urlencode($category['name']); ?>">Browse <?php echo escape($category['name']); ?></a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<section class="section">
    <div class="section-header">
        <div>
            <span class="section-kicker">Popularity report</span>
            <h2>Most populated categories</h2>
        </div>
    </div>
    <div class="report-grid">
        <?php foreach ($topCategories as $category): ?>
            <article class="report-card">
                <strong><?php echo escape($category['total']); ?></strong>
                <h2><?php echo escape($category['category']); ?></h2>
                <p>Movies currently available inside this category.</p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
