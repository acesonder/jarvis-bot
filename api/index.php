<?php
/**
 * Tweak Easy API
 * RESTful API Entry Point
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/config.php';

// API versioning
define('API_VERSION', 'v1');

// Start session
Session::start();

// Parse request
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$basePath = '/api/';

// Remove query string and base path
$path = parse_url($requestUri, PHP_URL_PATH);
$path = str_replace($basePath, '', $path);
$pathParts = array_filter(explode('/', $path));

// Route the request
if (empty($pathParts)) {
    Response::json([
        'name' => 'Tweak Easy API',
        'version' => APP_VERSION,
        'api_version' => API_VERSION,
        'endpoints' => [
            '/auth/login',
            '/auth/logout',
            '/auth/register',
            '/clients',
            '/orders',
            '/inventory',
            '/messages',
            '/appointments',
            '/referrals',
            '/reports'
        ]
    ]);
}

// Get the resource
$resource = array_shift($pathParts);
$action = array_shift($pathParts) ?? null;

// Include the appropriate controller
$controllerFile = __DIR__ . '/' . $resource . '/index.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
} else {
    Response::error('Resource not found', 404);
}
