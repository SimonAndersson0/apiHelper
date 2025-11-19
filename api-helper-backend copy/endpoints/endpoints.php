<?php
// endpoints/endpoints.php  (api endpoints management)
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../helpers/Response.php";

$start = microtime(true);
$db = Database::getInstance()->conn();
$method = $_SERVER['REQUEST_METHOD'];
$action = $segments[1] ?? $_GET['action'] ?? 'list';

// GET list by group: ?route=endpoints/list&group_id=1
if ($method === "GET" && ($action === "list" || $action === "get")) {
    if (isset($_GET['group_id'])) {
        $stmt = $db->prepare("SELECT * FROM api_endpoints WHERE group_id = :gid ORDER BY title");
        $stmt->execute([':gid' => (int)$_GET['group_id']]);
        $rows = $stmt->fetchAll();
        Response::success("Endpoints loaded", $rows, $start);
    } elseif (isset($_GET['endpoint_id'])) {
        $stmt = $db->prepare("SELECT * FROM api_endpoints WHERE id = :id");
        $stmt->execute([':id' => (int)$_GET['endpoint_id']]);
        $row = $stmt->fetch();
        if (!$row) Response::error("Endpoint not found", [], $start, 404);
        Response::success("Endpoint loaded", $row, $start);
    } else {
        $stmt = $db->query("SELECT * FROM api_endpoints ORDER BY title");
        Response::success("Endpoints loaded", $stmt->fetchAll(), $start);
    }
}

// create endpoint POST ?route=endpoints/add
if ($method === "POST" && ($action === "add" || $action === "create")) {
    $input = json_decode(file_get_contents("php://input"), true) ?? $_POST;
    $required = ['group_id','title','url','method'];
    foreach ($required as $r) if (empty($input[$r])) Response::error("$r is required", [], $start, 400);

    $stmt = $db->prepare("INSERT INTO api_endpoints (group_id, title, description, url, method) VALUES (:gid,:title,:desc,:url,:method)");
    $stmt->execute([
        ':gid' => (int)$input['group_id'],
        ':title' => $input['title'],
        ':desc' => $input['description'] ?? '',
        ':url' => $input['url'],
        ':method' => strtoupper($input['method'])
    ]);
    Response::success("Endpoint created", ['endpoint_id' => $db->lastInsertId()], $start);
}

Response::error("Unknown action for endpoints", [], $start, 400);
