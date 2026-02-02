<?php
session_start();
setcookie("remember_me", "", time() - 3600, "/"); // Delete cookie
session_unset();
session_destroy();

header("Location: ../pages/auth/login.php");
exit;
?>