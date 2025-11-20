<?php
require_once __DIR__ . '/helpers/Response.php';
header('Content-Type: application/json');

// Get endpoint from query parameter, e.g. ?endpoint=groups/create-group
$endpoint = $_GET['endpoint'] ?? '';
if (!$endpoint) Response::error('No endpoint specified', 400);

// Convert endpoint to lowercase and replace spaces/backslashes with hyphens / directory separator
$endpointPath = strtolower(str_replace(['/', '\\', ' '], DIRECTORY_SEPARATOR, $endpoint));
$endpointFile = __DIR__ . '/endpoints/' . $endpointPath . '.php';

if (!file_exists($endpointFile)) Response::error('Endpoint not found', 404);

// Detect request method and parse input
$requestMethod = $_SERVER['REQUEST_METHOD'];
if ($requestMethod === 'POST') {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $data = (strpos($contentType, 'application/json') !== false) 
        ? json_decode(file_get_contents('php://input'), true) ?? [] 
        : $_POST;
} else {
    $data = $_GET;
}

// Include endpoint file and execute
try {
    require $endpointFile;
} catch (Exception $e) {
    Response::error("Server error: " . $e->getMessage(), 500);
}
