<?php

$projectRoot = __DIR__;

// -- Preventing direct access and redirecting to the login page --
// Get the request URI (e.g., /path/to/page.php?param=value)
$uri = trim(dirname($_SERVER['REQUEST_URI']));
if ($uri == '\\') {
    require_once $projectRoot . '/helper/prevent-direct-access.php';
}

require_once $projectRoot . '/helper/security-check.php';
require_once $projectRoot . '/database/Database.php';
require_once $projectRoot . '/helper/Validator.php';
require_once $projectRoot . '/logic/User.php';