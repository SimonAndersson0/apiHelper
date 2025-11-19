<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../helpers/Response.php";

function endpoints($data) {
    $start = microtime(true);
    $db = Database::getInstance()->conn();
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $data['action'] ?? 'list';

    // List endpoints by group
    if ($method === "GET" && ($action === "list" || $action === "get")) {
        $sql = "SELECT * FROM endpoints";
        $params = [];
        if (!empty($data['group_id'])) {
            $sql .= " WHERE group_id = :gid";
            $params[':gid'] = (int)$data['group_id'];
        }
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        Response::success("Endpoints loaded", $stmt->fetchAll(), $start);
    }

    // Add endpoint
    if ($method === "POST" && ($action === "add" || $action === "create")) {
        if (empty($data['group_id']) || empty($data['title']) || empty($data['url']) || empty($data['method'])) {
            Response::error("group_id, title, url, and method are required", [], $start, 400);
        }

        $stmt = $db->prepare("INSERT INTO endpoints (group_id, title, url, method, description) VALUES (:gid, :title, :url, :method, :desc)");
        $stmt->execute([
            ':gid' => (int)$data['group_id'],
            ':title' => $data['title'],
            ':url' => $data['url'],
            ':method' => strtoupper($data['method']),
            ':desc' => $data['description'] ?? null
        ]);

        Response::success("Endpoint created", ['endpoint_id' => $db->lastInsertId()], $start);
    }

    Response::error("Unknown action for endpoints", [], $start, 400);
}
