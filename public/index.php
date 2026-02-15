<?php

require __DIR__ . '/../vendor/autoload.php';

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

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
    
    require_once __DIR__ . '/../app/Controllers/' . $controllerName . '.php';
    $controller = new $controllerName();
    $controller->$methodName();
} else {
    http_response_code(404);
    echo "404 - Page not found";
}
