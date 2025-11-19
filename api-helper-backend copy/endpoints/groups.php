<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../helpers/Response.php";

function groups($data) {
    $start = microtime(true);
    $db = Database::getInstance()->conn();
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $data['action'] ?? 'list';

    // List groups by project
    if ($method === "GET" && ($action === "list" || $action === "get")) {
        if (empty($data['project_id'])) Response::error("project_id is required", [], $start, 400);
        $stmt = $db->prepare("SELECT * FROM endpoint_groups WHERE project_id = :pid ORDER BY name");
        $stmt->execute([':pid' => (int)$data['project_id']]);
        $rows = $stmt->fetchAll();
        Response::success("Groups loaded", $rows, $start);
    }

    // Add group
    if ($method === "POST" && ($action === "add" || $action === "create")) {
        if (empty($data['project_id']) || empty($data['name'])) Response::error("project_id and name required", [], $start, 400);
        $stmt = $db->prepare("INSERT INTO endpoint_groups (project_id, name, parent_id) VALUES (:pid, :name, :parent)");
        $stmt->execute([
            ':pid' => (int)$data['project_id'],
            ':name' => $data['name'],
            ':parent' => !empty($data['parent_id']) ? (int)$data['parent_id'] : null
        ]);
        Response::success("Group created", ['group_id' => $db->lastInsertId()], $start);
    }

    Response::error("Unknown action for groups", [], $start, 400);
}
