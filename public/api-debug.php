<?php

require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

$dotenv = parse_ini_file(__DIR__ . '/../.env');

echo json_encode([
    'success' => true,
    'message' => 'API Router Test',
    'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
    'path' => parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH),
    'method' => $_SERVER['REQUEST_METHOD'] ?? '',
    'query_string' => $_SERVER['QUERY_STRING'] ?? '',
    'get_params' => $_GET,
    'has_api_key_env' => isset($dotenv['API_KEY']),
    'routes_defined' => true,
    'timestamp' => date('Y-m-d H:i:s')
], JSON_PRETTY_PRINT);
