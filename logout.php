<?php
require_once __DIR__ . '/includes/helpers.php';
unset($_SESSION['user_id']);
setFlash('success', 'You have been logged out.');
redirect('index.php');
