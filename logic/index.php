<?php
$projectRoot = str_replace("\logic", "", __DIR__);
// -- Preventing direct access and redirecting to the login page --
// Get the request URI (e.g., /path/to/page.php?param=value)
require_once $projectRoot . '/helper/prevent-direct-access.php';
?>