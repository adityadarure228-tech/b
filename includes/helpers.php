<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/config.php';

function isAdminLoggedIn(): bool
{
    return !empty($_SESSION['admin_logged_in']);
}

function isUserLoggedIn(): bool
{
    return !empty($_SESSION['user_id']);
}

function currentUser(): ?array
{
    global $conn;
    if (!isUserLoggedIn()) {
        return null;
    }

    $userId = (int) $_SESSION['user_id'];
    $result = $conn->query("SELECT id, name, username, email, created_at FROM users WHERE id = {$userId} LIMIT 1");
    return $result ? $result->fetch_assoc() : null;
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function escape(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function fetchAllMovies(?string $category = null): array
{
    global $conn;
    $sql = 'SELECT * FROM movies';
    if ($category) {
        $category = $conn->real_escape_string($category);
        $sql .= " WHERE category = '{$category}'";
    }
    $sql .= ' ORDER BY created_at DESC, id DESC';
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function fetchMovieById(int $id): ?array
{
    global $conn;
    $result = $conn->query("SELECT * FROM movies WHERE id = {$id} LIMIT 1");
    return $result && $result->num_rows ? $result->fetch_assoc() : null;
}

function fetchCategories(): array
{
    global $conn;
    $result = $conn->query('SELECT * FROM categories ORDER BY name ASC');
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function fetchWishlistMovies(int $userId): array
{
    global $conn;
    $sql = "SELECT w.id AS wishlist_id, m.* FROM wishlist w JOIN movies m ON m.id = w.movie_id WHERE w.user_id = {$userId} ORDER BY w.created_at DESC";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function countTable(string $table): int
{
    global $conn;
    $result = $conn->query("SELECT COUNT(*) AS total FROM {$table}");
    $row = $result ? $result->fetch_assoc() : ['total' => 0];
    return (int) ($row['total'] ?? 0);
}

function totalWishlistEntries(): int
{
    global $conn;
    $result = $conn->query('SELECT COUNT(*) AS total FROM wishlist');
    $row = $result ? $result->fetch_assoc() : ['total' => 0];
    return (int) ($row['total'] ?? 0);
}

function averageMovieRating(): string
{
    global $conn;
    $result = $conn->query('SELECT AVG(rating) AS avg_rating FROM movies');
    $row = $result ? $result->fetch_assoc() : ['avg_rating' => 0];
    return number_format((float) ($row['avg_rating'] ?? 0), 1);
}

function featuredMovie(): ?array
{
    global $conn;
    $result = $conn->query('SELECT * FROM movies ORDER BY rating DESC, id DESC LIMIT 1');
    return $result && $result->num_rows ? $result->fetch_assoc() : null;
}

function topCategories(): array
{
    global $conn;
    $sql = 'SELECT category, COUNT(*) AS total FROM movies GROUP BY category ORDER BY total DESC, category ASC LIMIT 6';
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function latestUsers(int $limit = 10): array
{
    global $conn;
    $limit = max(1, $limit);
    $result = $conn->query("SELECT id, name, username, email, created_at FROM users ORDER BY created_at DESC LIMIT {$limit}");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function latestMovies(int $limit = 10): array
{
    global $conn;
    $limit = max(1, $limit);
    $result = $conn->query("SELECT * FROM movies ORDER BY created_at DESC LIMIT {$limit}");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}
