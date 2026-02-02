<?php
// Check if the connection is secure (HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
// Get the host name (e.g., www.example.com)
$host = $_SERVER['HTTP_HOST'];
// Assemble the full URL
$domain = $protocol . "://" . $host;
// Set the new location
$new_url = $domain . "/pages/auth/login.php";


if (session_status() === PHP_SESSION_NONE) session_start();

// Security Check: Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $new_url);
    exit;
}
?>