<?php

require_once __DIR__ . "/../core/Controller.php";

class Projects extends Controller {

    public function getProjects() {
        $stmt = $this->db->query("SELECT * FROM projects ORDER BY name");
        $projects = $stmt->fetchAll();
        Response::success("Projects loaded", $projects);
    }

    public function createProject() {
        $input = $this->input();

        if (empty($input['name']))
            Response::error("Project name is required");

        $stmt = $this->db->prepare("INSERT INTO projects (name) VALUES (:name)");
        $stmt->execute([":name" => $input['name']]);

        Response::success("Project created", [
            "project_id" => $this->db->lastInsertId()
        ]);
    }
}

$controller = new Projects();

Router::route("GET",  "/api/projects/get",        [$controller, "getProjects"]);
Router::route("POST", "/api/projects/create",     [$controller, "createProject"]);
