<?php
/**
 * Development Server Router
 * This file routes requests for PHP's built-in web server
 */

// Get the requested URI
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Route API requests
if (strpos($uri, '/api/') === 0) {
    require __DIR__ . '/api/index.php';
    exit;
}

// For other PHP files, execute them
if (preg_match('/\.php$/', $uri)) {
    $file = __DIR__ . $uri;
    if (file_exists($file)) {
        require $file;
        exit;
    }
}

// Default to index.html
if ($uri === '/') {
    require __DIR__ . '/index.html';
    exit;
}

// 404 for everything else
http_response_code(404);
echo "404 - Not Found";
