<?php
mysqli_report(MYSQLI_REPORT_OFF);
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'movie_recommendation_system';

$serverConn = new mysqli($dbHost, $dbUser, $dbPass);
if ($serverConn->connect_error) {
    die('Database server connection failed: ' . $serverConn->connect_error);
}

$serverConn->query("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$serverConn->close();

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
require_once __DIR__ . '/includes/config.php';

$conn->query('CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL UNIQUE,
    image_url TEXT NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$conn->query('CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    username VARCHAR(80) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$conn->query('CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    category VARCHAR(80) NOT NULL,
    description TEXT NOT NULL,
    poster_url TEXT NOT NULL,
    backdrop_url TEXT NOT NULL,
    teaser_url TEXT NOT NULL,
    release_year INT NOT NULL,
    rating DECIMAL(3,1) NOT NULL DEFAULT 0,
    duration VARCHAR(30) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$conn->query('CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    movie_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_wishlist (user_id, movie_id),
    CONSTRAINT fk_wishlist_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_wishlist_movie FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$categoryImages = [
    'Sci-Fi' => 'https://images.unsplash.com/photo-1462331940025-496dfbfc7564?auto=format&fit=crop&w=1200&q=80',
    'Horror' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80',
    'Thriller' => 'https://images.unsplash.com/photo-1496307042754-b4aa456c4a2d?auto=format&fit=crop&w=1200&q=80',
    'Romantic' => 'https://images.unsplash.com/photo-1516589178581-6cd7833ae3b2?auto=format&fit=crop&w=1200&q=80',
    'Fantasy' => 'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=1200&q=80',
    'Action' => 'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=1200&q=80',
    'Adventure' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
    'Drama' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=1200&q=80',
    'Mystery' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=1200&q=80',
    'Animation' => 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=1200&q=80',
];

foreach ($defaultCategories as $category) {
    $name = $conn->real_escape_string($category);
    $description = $conn->real_escape_string($category . ' collection curated for cinematic discovery, teaser playback, and watchlist journeys.');
    $image = $conn->real_escape_string($categoryImages[$category] ?? 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=1200&q=80');
    $conn->query("INSERT IGNORE INTO categories (name, image_url, description) VALUES ('{$name}', '{$image}', '{$description}')");
}

$movieCountResult = $conn->query('SELECT COUNT(*) AS total FROM movies');
$movieCount = $movieCountResult ? (int) ($movieCountResult->fetch_assoc()['total'] ?? 0) : 0;
if ($movieCount === 0) {
    foreach ($defaultMovies as $movie) {
        $title = $conn->real_escape_string($movie['title']);
        $category = $conn->real_escape_string($movie['category']);
        $description = $conn->real_escape_string($movie['description']);
        $poster = $conn->real_escape_string($movie['poster_url']);
        $backdrop = $conn->real_escape_string($movie['backdrop_url']);
        $teaser = $conn->real_escape_string($movie['teaser_url']);
        $year = (int) $movie['release_year'];
        $rating = (float) $movie['rating'];
        $duration = $conn->real_escape_string($movie['duration']);
        $conn->query("INSERT INTO movies (title, category, description, poster_url, backdrop_url, teaser_url, release_year, rating, duration) VALUES ('{$title}', '{$category}', '{$description}', '{$poster}', '{$backdrop}', '{$teaser}', {$year}, {$rating}, '{$duration}')");
    }
}
