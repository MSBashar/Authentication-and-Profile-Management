<?php

$projectRoot = str_replace("\helper", "", __DIR__);

// -- Preventing direct access and redirecting to the login page --
// Get the request URI (e.g., /path/to/page.php?param=value)
$uri = trim(dirname($_SERVER['REQUEST_URI']));
if ($uri == '/helper' || $uri == '/helper/') {
    require_once $projectRoot . '/helper/prevent-direct-access.php';
}


class Validator {
    /**
     * Validate Name
     */
    public static function name($name) {
        $name = trim($name);
        if (empty($name)) return "Name is required.";
        if (strlen($name) < 2) return "Name must be at least 2 characters.";
        if (strlen($name) > 50) return "Name is too long.";
        return true;
    }

    /**
     * Validate Email
     */
    public static function email($email) {
        $email = trim($email);
        if (empty($email)) return "Email is required.";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Please enter a valid email address.";
        }
        return true;
    }

    /**
     * Validate Password (Strength check)
     */
    public static function password($password) {
        if (empty($password)) return "Password is required.";
        if (strlen($password) < 6) return "Password must be at least 6 characters.";
        // Optional: Add regex for numbers/symbols here
        return true;
    }

    /**
     * Sanitize Input (For General Strings)
     */
    public static function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}