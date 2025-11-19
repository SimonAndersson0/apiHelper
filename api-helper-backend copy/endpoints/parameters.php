<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../helpers/Response.php";

function parameters($data) {
    $start = microtime(true);
    $db = Database::getInstance()->conn();
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $data['action'] ?? 'list';

    // List parameters by variation
    if ($method === "GET" && ($action === "list" || $action === "get")) {
        if (empty($data['variation_id'])) Response::error("variation_id required", [], $start, 400);
        $stmt = $db->prepare("SELECT * FROM parameters WHERE variation_id = :vid ORDER BY name");
        $stmt->execute([':vid' => (int)$data['variation_id']]);
        Response::success("Parameters loaded", $stmt->fetchAll(), $start);
    }

    // Add parameter
    if ($method === "POST" && ($action === "add" || $action === "create")) {
        if (empty($data['variation_id']) || empty($data['name']) || empty($data['type'])) {
            Response::error("variation_id, name, and type required", [], $start, 400);
        }

        $stmt = $db->prepare("INSERT INTO parameters (variation_id, name, type, required, description) VALUES (:vid, :name, :type, :req, :desc)");
        $stmt->execute([
            ':vid' => (int)$data['variation_id'],
            ':name' => $data['name'],
            ':type' => $data['type'],
            ':req' => !empty($data['required']) ? 1 : 0,
            ':desc' => $data['description'] ?? null
        ]);

        Response::success("Parameter created", ['parameter_id' => $db->lastInsertId()], $start);
    }

    Response::error("Unknown action for parameters", [], $start, 400);
}
