<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../helpers/Response.php";

function variations($data) {
    $start = microtime(true);
    $db = Database::getInstance()->conn();
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $data['action'] ?? 'list';

    // List variations by endpoint
    if ($method === "GET" && ($action === "list" || $action === "get")) {
        if (empty($data['endpoint_id'])) Response::error("endpoint_id required", [], $start, 400);
        $stmt = $db->prepare("SELECT * FROM variations WHERE endpoint_id = :eid ORDER BY title");
        $stmt->execute([':eid' => (int)$data['endpoint_id']]);
        Response::success("Variations loaded", $stmt->fetchAll(), $start);
    }

    // Add variation
    if ($method === "POST" && ($action === "add" || $action === "create")) {
        if (empty($data['endpoint_id']) || empty($data['title'])) Response::error("endpoint_id and title required", [], $start, 400);
        $stmt = $db->prepare("INSERT INTO variations (endpoint_id, title, description) VALUES (:eid, :title, :desc)");
        $stmt->execute([
            ':eid' => (int)$data['endpoint_id'],
            ':title' => $data['title'],
            ':desc' => $data['description'] ?? null
        ]);
        Response::success("Variation created", ['variation_id' => $db->lastInsertId()], $start);
    }

    Response::error("Unknown action for variations", [], $start, 400);
}
