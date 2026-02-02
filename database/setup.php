<?php
require_once 'Database.php';

try {
    // 1. Connect to MySQL
    $database = new Database();
    $dbSettings = $database->getDBSettings();
    $host = $dbSettings['host'];
    $username = $dbSettings['username'];
    $password = $dbSettings['password'];
    $dbName = $dbSettings['db_name'];

    // 2. Create Database
    $pdo = new PDO("mysql:host=$host;user=$username;password=$password");
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo '✔ Database "' . $dbName . '" created or already exists.<br>';

    // 3. Connect to the specific DB
    $pdo->exec("USE `$dbName`");

    // 4. Create Users Table
    $sqlUsers = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        remember_token VARCHAR(255) NULL, 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";

    // 5. Create password_resets table
    $sqlResets = "CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) NOT NULL,
        token VARCHAR(255) NOT NULL,
        expires_at DATETIME NOT NULL
    ) ENGINE=InnoDB";

    $pdo->exec($sqlUsers);
    $pdo->exec($sqlResets);
    echo "✔ Table 'users' created or already exists.<br>";
    echo "✔ Table 'password_resets' created or already exists.<br>";
    echo "<br><strong>Setup Complete!</strong> You can now <a href='../pages/auth/signup.php'>Register here</a>.";

} catch (PDOException $e) {
    die("❌ Setup Error: " . $e->getMessage());
}
?>