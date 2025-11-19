<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../helpers/Response.php";

function projects($data) {
    $start = microtime(true);
    $db = Database::getInstance()->conn();
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $data['action'] ?? 'list';

    // List projects: GET ?endpoint=projects&action=list
    if ($method === "GET" && ($action === "list" || $action === "get")) {
        $stmt = $db->query("SELECT * FROM projects ORDER BY name");
        $rows = $stmt->fetchAll();
        Response::success("Projects loaded", $rows, $start);
    }

    // Add project: POST ?endpoint=projects&action=add
    if ($method === "POST" && ($action === "add" || $action === "create")) {
        if (empty($data['name'])) Response::error("Project name required", [], $start, 400);

        $stmt = $db->prepare("INSERT INTO projects (name, description) VALUES (:name, :desc)");
        $stmt->execute([
            ':name' => $data['name'],
            ':desc' => $data['description'] ?? null
        ]);

        Response::success("Project created", ['project_id' => $db->lastInsertId()], $start);
    }

    Response::error("Unknown action for projects", [], $start, 400);
}
