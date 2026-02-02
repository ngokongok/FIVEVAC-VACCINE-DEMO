<?php
// router.php
// This script is intended for use with PHP's built-in web server.
// It emulates the Apache mod_rewrite rules defined in public/.htaccess
// by intercepting requests and routing them through the front controller.

// Parse the requested URI and decode it
$uri  = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Define the path to the public directory
$root = __DIR__ . '/public';

// Serve existing files directly (e.g. CSS, JS, images)
if ($uri !== '/' && file_exists($root . $uri)) {
    return false;
}

// Otherwise, set the 'url' parameter expected by index.php. When a 'url'
// query parameter exists (e.g. index.php?url=consult/request), use that
// value instead of the path. This allows GET requests with additional
// query parameters to be routed correctly through index.php.

// Parse the query string for a 'url' parameter
$queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
parse_str($queryString ?? '', $queryParams);
if (!empty($queryParams['url'])) {
    // Use the 'url' from the query string (remove leading slash if present)
    $_GET['url'] = ltrim($queryParams['url'], '/');
} else {
    // Fallback: derive from the URI path
    $_GET['url'] = ltrim($uri, '/');
}

// Include the main front controller
require $root . '/index.php';