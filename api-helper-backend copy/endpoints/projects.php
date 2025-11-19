<?php
// endpoints/projects.php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../helpers/Response.php";

$start = microtime(true);
$db = Database::getInstance()->conn();
$method = $_SERVER['REQUEST_METHOD'];
$action = $segments[1] ?? $_GET['action'] ?? 'list';

// GET /?route=projects/list  or POST /?route=projects/add
if ($method === "GET" && ($action === "list" || $action === "get")) {
    // optional: ?project_id=1
    if (isset($_GET['project_id'])) {
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = :id");
        $stmt->execute([':id' => (int)$_GET['project_id']]);
        $project = $stmt->fetch();
        if (!$project) Response::error("Project not found", [], $start, 404);
        Response::success("Project loaded", $project, $start);
    } else {
        $stmt = $db->query("SELECT * FROM projects ORDER BY name");
        $rows = $stmt->fetchAll();
        Response::success("Projects loaded", $rows, $start);
    }
}

// POST /?route=projects/add
if ($method === "POST" && ($action === "add" || $action === "create")) {
    $input = json_decode(file_get_contents("php://input"), true) ?? $_POST;
    if (empty($input['name'])) Response::error("Project name required", [], $start, 400);

    $stmt = $db->prepare("INSERT INTO projects (name, description) VALUES (:name, :desc)");
    $stmt->execute([
        ':name' => $input['name'],
        ':desc' => $input['description'] ?? ''
    ]);
    $id = $db->lastInsertId();
    Response::success("Project created", ['project_id' => $id], $start);
}

// fallback
Response::error("Unknown action for projects", [], $start, 400);
