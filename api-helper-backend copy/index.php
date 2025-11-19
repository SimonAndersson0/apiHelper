<?php

// index.php

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/helpers/Response.php';

header('Content-Type: application/json');

// Get endpoint from query parameter, e.g. ?endpoint=create_user
$endpoint = $_GET['endpoint'] ?? '';

if (empty($endpoint)) {
    Response::error('No endpoint specified', 400);
}

// Convert endpoint name to file path
$endpointFile = __DIR__ . '/endpoints/' . $endpoint . '.php';

if (!file_exists($endpointFile)) {
    Response::error('Endpoint file not found', 404);
}

require_once $endpointFile;

// Call the function if it exists
if (!function_exists($endpoint)) {
    Response::error('Endpoint function not implemented', 501);
}

// Decide whether to use GET or POST data
$data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

try {
    $endpoint($data);
} catch (Exception $e) {
    Response::error($e->getMessage(), 500);
}
?>

