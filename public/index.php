<?php

// Security: Prevent direct access to files outside public directory
if (strpos($_SERVER['REQUEST_URI'], '..') !== false) {
    http_response_code(403);
    exit('Forbidden');
}

// Security headers
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Prevent PHP version exposure
header_remove('X-Powered-By');

require __DIR__ . '/../vendor/autoload.php';

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Sanitize path
$path = filter_var($path, FILTER_SANITIZE_URL);

$routes = [
    'GET' => [
        '/' => ['controller' => 'HomeController', 'method' => 'index'],
        '/login' => ['controller' => 'AuthController', 'method' => 'login'],
        '/admin/login' => ['controller' => 'AuthController', 'method' => 'login'],
        '/admin/dashboard' => ['controller' => 'AuthController', 'method' => 'dashboard'],
        '/logout' => ['controller' => 'AuthController', 'method' => 'logout'],
    ],
    'POST' => [
        '/login' => ['controller' => 'AuthController', 'method' => 'authenticate'],
        '/admin/login' => ['controller' => 'AuthController', 'method' => 'authenticate'],
    ]
];

if (isset($routes[$method][$path])) {
    $route = $routes[$method][$path];
    $controllerName = $route['controller'];
    $methodName = $route['method'];
    
    $controllerFile = __DIR__ . '/../app/Controllers/' . $controllerName . '.php';
    
    // Security: Verify file exists and is within allowed directory
    if (!file_exists($controllerFile) || strpos(realpath($controllerFile), realpath(__DIR__ . '/../app/Controllers/')) !== 0) {
        http_response_code(404);
        exit('404 - Page not found');
    }
    
    require_once $controllerFile;
    
    // Security: Verify class and method exist
    if (!class_exists($controllerName) || !method_exists($controllerName, $methodName)) {
        http_response_code(500);
        exit('500 - Internal Server Error');
    }
    
    $controller = new $controllerName();
    $controller->$methodName();
} else {
    http_response_code(404);
    echo "404 - Page not found";
}
