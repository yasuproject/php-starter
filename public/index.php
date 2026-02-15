<?php

require __DIR__ . '/../vendor/autoload.php';

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

$routes = [
    '/' => ['controller' => 'HomeController', 'method' => 'index'],
    '/login' => ['controller' => 'AuthController', 'method' => 'login'],
    '/admin/login' => ['controller' => 'AuthController', 'method' => 'login'],
];

if (isset($routes[$path])) {
    $controllerName = $routes[$path]['controller'];
    $methodName = $routes[$path]['method'];
    
    require_once __DIR__ . '/../app/Controllers/' . $controllerName . '.php';
    $controller = new $controllerName();
    $controller->$methodName();
} else {
    http_response_code(404);
    echo "404 - Page not found";
}
