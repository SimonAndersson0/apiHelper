<?php
// endpoints/variations.php - variations (test cases) for endpoints
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../helpers/Response.php";

$start = microtime(true);
$db = Database::getInstance()->conn();
$method = $_SERVER['REQUEST_METHOD'];
$action = $segments[1] ?? $_GET['action'] ?? 'list';

// List variations by endpoint: GET ?route=variations/list&endpoint_id=1
if ($method === "GET" && ($action === "list" || $action === "get")) {
    if (empty($_GET['endpoint_id'])) Response::error("endpoint_id required", [], $start, 400);
    $stmt = $db->prepare("SELECT * FROM endpoint_variations WHERE endpoint_id = :eid ORDER BY variation_name");
    $stmt->execute([':eid' => (int)$_GET['endpoint_id']]);
    Response::success("Variations loaded", $stmt->fetchAll(), $start);
}

// Create variation: POST ?route=variations/add
if ($method === "POST" && ($action === "add" || $action === "create")) {
    $input = json_decode(file_get_contents("php://input"), true) ?? $_POST;
    if (empty($input['endpoint_id']) || empty($input['variation_name'])) Response::error("endpoint_id and variation_name required", [], $start, 400);

    $stmt = $db->prepare("INSERT INTO endpoint_variations (endpoint_id, variation_name, description, example_body, depends_on) VALUES (:eid,:vname,:desc,:body,:depends)");
    $stmt->execute([
        ':eid' => (int)$input['endpoint_id'],
        ':vname' => $input['variation_name'],
        ':desc' => $input['description'] ?? '',
        ':body' => !empty($input['example_body']) ? json_encode($input['example_body']) : null,
        ':depends' => !empty($input['depends_on']) ? (is_array($input['depends_on']) ? implode(',', $input['depends_on']) : $input['depends_on']) : null
    ]);
    Response::success("Variation created", ['variation_id' => $db->lastInsertId()], $start);
}

Response::error("Unknown action for variations", [], $start, 400);
