<?php
require_once __DIR__ . '/helpers/response.php';
header('Content-Type: application/json');

$endpoint = $_GET['endpoint'] ?? '';
if (!$endpoint) Response::error('No endpoint specified', 400);

$endpointFile = __DIR__ . '/endpoints/' . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $endpoint) . '.php';
if (!file_exists($endpointFile)) Response::error('Endpoint not found', 404);

$requestMethod = $_SERVER['REQUEST_METHOD'];
if ($requestMethod === 'POST') {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $data = (strpos($contentType, 'application/json') !== false) 
        ? json_decode(file_get_contents('php://input'), true) ?? [] 
        : $_POST;
} else {
    $data = $_GET;
}

try {
    require $endpointFile;
} catch (Exception $e) {
    Response::error($e->getMessage(), 500);
}
