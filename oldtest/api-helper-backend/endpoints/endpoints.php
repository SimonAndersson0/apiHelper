<?php

require_once __DIR__ . "/../core/Controller.php";

class ApiEndpoints extends Controller {

    public function createEndpoint() {
        $input = $this->input();

        if (empty($input['project_id']) || empty($input['title']))
            Response::error("project_id and title required");

        $stmt = $this->db->prepare("
            INSERT INTO api_endpoints (project_id, title, description, url, method)
            VALUES (:project_id, :title, :desc, :url, :method)
        ");

        $stmt->execute([
            ":project_id" => $input['project_id'],
            ":title"      => $input['title'],
            ":desc"       => $input['description'] ?? "",
            ":url"        => $input['url'] ?? "",
            ":method"     => strtoupper($input['method'] ?? "POST")
        ]);

        Response::success("Endpoint added", [
            "endpoint_id" => $this->db->lastInsertId()
        ]);
    }
}

$controller = new ApiEndpoints();
Router::route("POST", "/api/endpoint/create", [$controller, "createEndpoint"]);
