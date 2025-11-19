<?php
// index.php

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/helpers/Response.php';

header('Content-Type: application/json');

// Get endpoint from query parameter, e.g. ?endpoint=user/create
$endpoint = $_GET['endpoint'] ?? '';

if (empty($endpoint)) {
    Response::error('No endpoint specified', 400);
}

// Convert endpoint name to file path, allowing nested folders
$endpointFile = __DIR__ . '/endpoints/' . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $endpoint) . '.php';

if (!file_exists($endpointFile)) {
    Response::error('Endpoint file not found', 404);
}

require_once $endpointFile;

// Function name is derived from last part of endpoint path
$endpointFunction = basename($endpoint);

if (!function_exists($endpointFunction)) {
    Response::error('Endpoint function not implemented', 501);
}

// Handle POST JSON bodies or GET/POST form data
$requestMethod = $_SERVER['REQUEST_METHOD'];
if ($requestMethod === 'POST') {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') !== false) {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null) $data = []; // fallback if JSON invalid
    } else {
        $data = $_POST;
    }
} else {
    $data = $_GET;
}

// Call the endpoint function
try {
    $endpointFunction($data);
} catch (Exception $e) {
    Response::error($e->getMessage(), 500);
}
?>
