<?php

$projectRoot = str_replace("\logic", "", __DIR__);

// -- Preventing direct access and redirecting to the login page --
// Get the request URI (e.g., /path/to/page.php?param=value)
$uri = trim(dirname($_SERVER['REQUEST_URI']));
if ($uri == '/logic' || $uri == '/logic/') {
    require_once $projectRoot . '/helper/prevent-direct-access.php';
}

require_once $projectRoot . '/helper/Validator.php';

class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. User Registration
    public function register($name, $email, $password, $confirm_password) {
        // Check if email already exists
        if ($this->emailExists($email)) {
            return ["error", "Email already exists."];
        }

        // Check if passwords match
        if ($password !== $confirm_password) {
            return ["error", "Passwords do not match."];
        }

        // Validate Inputs
        $nameCheck = Validator::name($name);
        if ($nameCheck !== true) return ["error", $nameCheck];

        $emailCheck = Validator::email($email);
        if ($emailCheck !== true) return ["error", $emailCheck];

        $passwordCheck = Validator::password($password);
        if ($passwordCheck !== true) return ["error", $passwordCheck];


        $query = "INSERT INTO " . $this->table_name . " (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query);

        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Sanitize and bind
        $stmt->bindParam(":name", htmlspecialchars(strip_tags($name)));
        $stmt->bindParam(":email", htmlspecialchars(strip_tags($email)));
        $stmt->bindParam(":password", $hashed_password);

        if ($stmt->execute()) {
            return true;
        }
        return ["error", "Registration failed."];
    }

    // 2. User Login
    public function login($email, $password) {
        
        // Validate Inputs
        $emailCheck = Validator::email($email);
        if ($emailCheck !== true) return ["error", $emailCheck];

        $passwordCheck = Validator::password($password);
        if ($passwordCheck !== true) return ["error", $passwordCheck];

        $query = "SELECT id, name, password FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":email", htmlspecialchars(strip_tags($email)));
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            // Verify Password
            if (password_verify($password, $row['password'])) {
                // Start Session
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                // Clearing the password reset token, if any.
                $this->deleteResetToken($email);
                return true;
            }
        }
        return false;
    }

    // 3. Get User Data
    public function getUser($id) {
        $query = "SELECT id, name, email FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Update Profile
    public function updateProfile($id, $name, $email) {

        // Validate Inputs
        $nameCheck = Validator::name($name);
        if ($nameCheck !== true) return ["error", $nameCheck];

        $emailCheck = Validator::email($email);
        if ($emailCheck !== true) return ["error", $emailCheck];

        
        // Check if email is taken by someone else
        if ($this->emailExists($email) && $this->emailExists($email) != $id) {
            return ["error", "Email is already in use by another account."];
        }

        $query = "UPDATE " . $this->table_name . " SET name = :name, email = :email WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        // Sanitize and bind
        $stmt->bindValue(":name", htmlspecialchars(strip_tags($name)));
        $stmt->bindValue(":email", htmlspecialchars(strip_tags($email)));        
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            return true;
        }
        return ["error", "Failed to update profile."];

    }

    // 5. Update Password
    public function updatePassword($id, $current_password, $password, $confirm_password) {

        // Validate Inputs
        $passwordCheck = Validator::password($password);
        if ($passwordCheck !== true) return ["error", $passwordCheck];

        // Fetch current password hash
        $query = "SELECT password FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || !password_verify($current_password, $row['password'])) {
            return ["error", "Current password is incorrect."];
        }
        if ($password !== $confirm_password) {
            return ["error", "Passwords do not match."];
        }

        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";

        $stmt = $this->conn->prepare($query);       
        $stmt->bindParam(":id", $id);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(":password", $hashed_password);

        if ($stmt->execute()) {
            return true;
        }
        return ["error", "Failed to update password."];
    }

    private function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":email", htmlspecialchars(strip_tags($email)));
        $stmt->execute();
        return $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC)['id'] : false;
    }

    public function updateRememberToken($userId, $token) {
        $query = "UPDATE " . $this->table_name . " SET remember_token = :token WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":id", $userId);
        return $stmt->execute();
    }

    public function getUserByToken($token) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE remember_token = :token LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPasswordReset($email, $token) {
        date_default_timezone_set('Asia/Dhaka');
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));
        $query = "INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expiry)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":email", htmlspecialchars(strip_tags($email)));
        $stmt->bindValue(":token", $token);
        $stmt->bindValue(":expiry", $expiry);
        return $stmt->execute();
    }

    public function verifyResetToken($email, $token) {
        $query = "SELECT * FROM password_resets 
                WHERE email = :email AND token = :token 
                AND expires_at > NOW() LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":email", htmlspecialchars(strip_tags($email)));
        $stmt->bindValue(":token", $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function resetPassword($email, $newPassword) {
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        
        $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bindValue(":password", $hashed_password);
        $stmt->bindValue(":email", htmlspecialchars(strip_tags($email)));
        
        return $stmt->execute();
    }

    public function deleteResetToken($email) {
        $query = "DELETE FROM password_resets WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":email", htmlspecialchars(strip_tags($email)));
        return $stmt->execute();
    }


}
?>