<?php
require_once __DIR__ . '/includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string(trim($_POST['name'] ?? ''));
    $username = $conn->real_escape_string(trim($_POST['username'] ?? ''));
    $email = $conn->real_escape_string(trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    if ($name === '' || $username === '' || $email === '' || $password === '') {
        setFlash('error', 'Please fill in all user fields.');
        redirect('register.php');
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $hashed = $conn->real_escape_string($hashed);
    $inserted = $conn->query("INSERT INTO users (name, username, password, email) VALUES ('{$name}', '{$username}', '{$hashed}', '{$email}')");

    if (!$inserted) {
        setFlash('error', 'Username or email already exists.');
        redirect('register.php');
    }

    setFlash('success', 'Registration completed. Please login.');
    redirect('login.php');
}
?>
<section class="page-intro glass-panel">
    <span class="section-kicker">Register</span>
    <h1>Create viewer account</h1>
    <p>Register with name, username, password, and email to save your favorite movies into the wishlist.</p>
</section>
<section class="section auth-grid">
    <div class="form-card">
        <h2>User Registration</h2>
        <form method="post" class="form-grid">
            <div class="form-field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" required>
            </div>
            <div class="form-field">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" required>
            </div>
            <div class="form-field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required>
            </div>
            <div class="form-field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>
            <div class="form-field full">
                <button class="btn btn-primary" type="submit">Register</button>
            </div>
        </form>
    </div>
    <div class="form-card">
        <h2>Why create an account?</h2>
        <p>Registered users can build a movie watchlist, return later to continue exploring, and discover teasers inside animated movie cards.</p>
        <div class="hero-stats">
            <article class="stat-card"><strong>01</strong><span>Name</span></article>
            <article class="stat-card"><strong>02</strong><span>Username</span></article>
            <article class="stat-card"><strong>03</strong><span>Email + Password</span></article>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
