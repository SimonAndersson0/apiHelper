<?php
require_once __DIR__ . '/base-handler.php';

class ProjectHandler extends BaseHandler {

    public function listProjects() {
        $stmt = $this->db->query("SELECT * FROM projects ORDER BY name");
        Response::success("Projects loaded", $stmt->fetchAll());
    }

    public function getProject($id) {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        Response::success("Project loaded", $stmt->fetch());
    }

    public function createProject($name, $description = null) {
        $stmt = $this->db->prepare("INSERT INTO projects (name, description) VALUES (:name, :desc)");
        $stmt->execute([':name'=>$name, ':desc'=>$description]);
        Response::success("Project created", ['id'=>$this->db->lastInsertId()]);
    }

    public function editProject($id, $name = null, $description = null) {
        $stmt = $this->db->prepare("UPDATE projects SET name=:name, description=:desc WHERE id=:id");
        $stmt->execute([':id'=>$id, ':name'=>$name, ':desc'=>$description]);
        Response::success("Project updated");
    }

    public function deleteProject($id) {
        $stmt = $this->db->prepare("DELETE FROM projects WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        Response::success("Project deleted");
    }
}
