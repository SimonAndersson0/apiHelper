<?php
// endpoints/parameters.php - parameter docs for endpoints (and optional variation-specific)
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../helpers/Response.php";

$start = microtime(true);
$db = Database::getInstance()->conn();
$method = $_SERVER['REQUEST_METHOD'];
$action = $segments[1] ?? $_GET['action'] ?? 'list';

// GET parameters for endpoint: ?route=parameters/list&endpoint_id=1
if ($method === "GET" && ($action === "list" || $action === "get")) {
    if (empty($_GET['endpoint_id'])) Response::error("endpoint_id required", [], $start, 400);
    $stmt = $db->prepare("SELECT * FROM endpoint_parameters WHERE endpoint_id = :eid ORDER BY name");
    $stmt->execute([':eid' => (int)$_GET['endpoint_id']]);
    Response::success("Parameters loaded", $stmt->fetchAll(), $start);
}

// POST add parameter: ?route=parameters/add
if ($method === "POST" && ($action === "add" || $action === "create")) {
    $input = json_decode(file_get_contents("php://input"), true) ?? $_POST;
    $required = ['endpoint_id','name','type'];
    foreach ($required as $r) if (empty($input[$r])) Response::error("$r is required", [], $start, 400);

    $stmt = $db->prepare("INSERT INTO endpoint_parameters (endpoint_id, variation_id, name, type, required, description) VALUES (:eid,:vid,:name,:type,:required,:desc)");
    $stmt->execute([
        ':eid' => (int)$input['endpoint_id'],
        ':vid' => !empty($input['variation_id']) ? (int)$input['variation_id'] : null,
        ':name' => $input['name'],
        ':type' => $input['type'],
        ':required' => $input['required'] ?? 'no',
        ':desc' => $input['description'] ?? ''
    ]);
    Response::success("Parameter created", ['param_id' => $db->lastInsertId()], $start);
}

Response::error("Unknown action for parameters", [], $start, 400);
