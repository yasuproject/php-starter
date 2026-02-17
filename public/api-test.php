<?php

header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'message' => 'Direct test file working',
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'not set',
    'path' => parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH),
    'method' => $_SERVER['REQUEST_METHOD'] ?? 'not set',
    'get_params' => $_GET,
    'timestamp' => date('Y-m-d H:i:s')
], JSON_PRETTY_PRINT);
