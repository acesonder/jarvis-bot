<?php
/**
 * Development Server Router
 * This file routes requests for PHP's built-in web server
 */

// Get the requested URI
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Security: Prevent directory traversal
if (strpos($uri, '..') !== false) {
    http_response_code(403);
    echo "403 - Forbidden";
    exit;
}

// Whitelist of allowed static file extensions
$allowedExtensions = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'woff', 'woff2', 'ttf', 'eot', 'ico'];

// Serve static files directly (only from allowed directories)
if ($uri !== '/') {
    $filePath = __DIR__ . $uri;
    $extension = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
    
    // Only serve files from assets directory or with allowed extensions
    if (file_exists($filePath) && 
        (strpos($uri, '/assets/') === 0 || in_array($extension, $allowedExtensions))) {
        return false;
    }
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
