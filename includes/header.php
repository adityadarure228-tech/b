<?php
require_once __DIR__ . '/helpers.php';
$flash = getFlash();
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape($siteName); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="scroll-bg" data-scroll-bg></div>
<div class="blur-backdrop"></div>
<div class="page-shell">
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="index.php">
                <span class="brand-badge"><strong>MR</strong></span>
                <span>
                    <span class="brand-title"><?php echo escape($siteName); ?></span>
                    <span class="brand-subtitle">3D movie discovery with trailers, wishlist, and admin dashboard</span>
                </span>
            </a>
            <nav class="nav-links">
                <a class="nav-link" href="index.php">Home</a>
                <a class="nav-link" href="movies.php">Movies</a>
                <a class="nav-link" href="categories.php">Categories</a>
                <a class="nav-link" href="wishlist.php">Wishlist</a>
                <a class="nav-link" href="reports.php">Reports</a>
                <?php if (isUserLoggedIn()): ?>
                    <span class="tag badge-user">Hi, <?php echo escape($user['name'] ?? 'User'); ?></span>
                    <a class="btn btn-secondary" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="btn btn-secondary" href="login.php">Login</a>
                    <a class="btn btn-primary" href="register.php">Register</a>
                <?php endif; ?>
                <?php if (isAdminLoggedIn()): ?>
                    <a class="btn btn-primary" href="admin.php">Admin Panel</a>
                <?php else: ?>
                    <a class="btn btn-secondary" href="admin.php">Admin Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <?php if ($flash): ?>
                <div class="alert <?php echo $flash['type'] === 'success' ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo escape($flash['message']); ?>
                </div>
            <?php endif; ?>
