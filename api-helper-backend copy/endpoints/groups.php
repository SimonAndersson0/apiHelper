<?php
// endpoints/groups.php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../helpers/Response.php";

$start = microtime(true);
$db = Database::getInstance()->conn();
$method = $_SERVER['REQUEST_METHOD'];
$action = $segments[1] ?? $_GET['action'] ?? 'list';

// List groups by project: GET ?route=groups/list&project_id=1
if ($method === "GET" && ($action === "list" || $action === "get")) {
    if (empty($_GET['project_id'])) Response::error("project_id is required", [], $start, 400);
    $stmt = $db->prepare("SELECT * FROM endpoint_groups WHERE project_id = :pid ORDER BY name");
    $stmt->execute([':pid' => (int)$_GET['project_id']]);
    $rows = $stmt->fetchAll();
    Response::success("Groups loaded", $rows, $start);
}

// Add group: POST ?route=groups/add
if ($method === "POST" && ($action === "add" || $action === "create")) {
    $input = json_decode(file_get_contents("php://input"), true) ?? $_POST;
    if (empty($input['project_id']) || empty($input['name'])) Response::error("project_id and name required", [], $start, 400);

    $stmt = $db->prepare("INSERT INTO endpoint_groups (project_id, name, parent_id) VALUES (:pid, :name, :parent)");
    $stmt->execute([
        ':pid' => (int)$input['project_id'],
        ':name' => $input['name'],
        ':parent' => !empty($input['parent_id']) ? (int)$input['parent_id'] : null
    ]);
    Response::success("Group created", ['group_id' => $db->lastInsertId()], $start);
}

Response::error("Unknown action for groups", [], $start, 400);
