<?php

$projectRoot = str_replace("\database", "", __DIR__);

// -- Preventing direct access and redirecting to the login page --
// Get the request URI (e.g., /path/to/page.php?param=value)
$uri = trim(dirname($_SERVER['REQUEST_URI']));
if ($uri == '/database' || $uri == '/database/') {
    require_once $projectRoot . '/helper/prevent-direct-access.php';
}

class Database {
    private $host = "localhost";
    private $db_name = "auth-and-profile-management-system";
    private $username = "root";
    private $password = "";
    public $conn;

    // Getter method to access the private database settings
    public function getDBSettings() {
        return [
            'host' => $this->host,
            'db_name' => $this->db_name,
            'username' => $this->username,
            'password' => $this->password
        ];
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            // Enable error reporting
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}