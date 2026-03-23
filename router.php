<?php
/**
 * Router script for PHP built-in development server.
 * Usage: php -S 0.0.0.0:8080 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly.
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Route everything else through index.php.
$_SERVER['PATH_INFO'] = $uri;
require __DIR__ . '/index.php';
