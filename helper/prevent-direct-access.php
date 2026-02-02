<?php

// -- Preventing direct access and redirecting to the login page --

// Check if the connection is secure (HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
// Get the host name (e.g., www.example.com)
$host = $_SERVER['HTTP_HOST'];
// Assemble the full URL
$domain = $protocol . "://" . $host;
// Set the new location
$new_url = $domain . "/pages/auth/login.php";

// Set the HTTP status code to 301 (Moved Permanently) and redirect to login page
header("Location: " . $new_url, true, 301);
// Stop further script execution
exit();

?>