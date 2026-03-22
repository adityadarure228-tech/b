<?php
require_once __DIR__ . '/includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string(trim($_POST['username'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    if ($username === '' || $password === '') {
        setFlash('error', 'Please enter username and password.');
        redirect('login.php');
    }
    $result = $conn->query("SELECT * FROM users WHERE username = '{$username}' LIMIT 1");
    $userData = $result && $result->num_rows ? $result->fetch_assoc() : null;
    if (!$userData || !password_verify($password, $userData['password'])) {
        setFlash('error', 'Invalid login details.');
        redirect('login.php');
    }
    $_SESSION['user_id'] = (int) $userData['id'];
    setFlash('success', 'Logged in successfully.');
    redirect('index.php');
}
?>
<section class="page-intro glass-panel">
    <span class="section-kicker">Login</span>
    <h1>User sign in</h1>
    <p>Login with your username and password, or use the separate admin login for the fixed administrator credentials.</p>
</section>
<section class="section auth-grid">
    <div class="form-card">
        <h2>User Login</h2>
        <p>Enter your account details to save recommendations, keep a wishlist, and explore the movie catalog.</p>
        <form method="post" class="form-grid">
            <div class="form-field full">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" placeholder="Enter username" required>
            </div>
            <div class="form-field full">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="Enter password" required>
            </div>
            <div class="form-field full">
                <button class="btn btn-primary" type="submit">Login</button>
            </div>
        </form>
    </div>
    <div class="form-card">
        <h2>Admin Login Details</h2>
        <p>Use the admin panel button in the header or go directly to the admin page with these credentials.</p>
        <div class="report-tags">
            <span class="tag badge-admin">Email: adi@gmail.com</span>
            <span class="tag badge-admin">Password: 123</span>
        </div>
        <p style="margin-top:16px;">Need an account first? Register with your name, username, password, and email.</p>
        <a class="btn btn-secondary" href="register.php">Go to register</a>
        <a class="btn btn-primary" href="admin.php">Open admin login</a>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
