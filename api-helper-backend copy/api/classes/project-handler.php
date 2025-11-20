<?php
require_once __DIR__ . '/BaseHandler.php';
require_once __DIR__ . '/../helpers/Response.php';

class ProjectHandler extends BaseHandler {

    public function createProject($name, $description = null) {
        try {
            $stmt = $this->db->prepare("INSERT INTO projects (name, description) VALUES (:name, :desc)");
            $stmt->execute([':name'=>$name, ':desc'=>$description]);
            return Response::success("Project created", ['id'=>$this->db->lastInsertId()]);
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function editProject($id, $name = null, $description = null) {
        try {
            $fields = [];
            $params = [':id'=>$id];
            $optionalFields = ['name','description'];
            foreach($optionalFields as $field){
                if($$field !== null){
                    $fields[] = "$field = :$field";
                    $params[":$field"] = $$field;
                }
            }
            if(empty($fields)) return Response::error("No fields to update");
            $sql = "UPDATE projects SET ".implode(", ", $fields)." WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return Response::success("Project updated");
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function deleteProject($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM projects WHERE id = :id");
            $stmt->execute([':id'=>$id]);
            return Response::success("Project deleted");
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function getProject($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = :id");
            $stmt->execute([':id'=>$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$data) return Response::error("Project not found", [], 404);
            return Response::success("Project loaded", $data);
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function listProjects() {
        try {
            $stmt = $this->db->query("SELECT * FROM projects ORDER BY name");
            return Response::success("Projects loaded", $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }
}
?>
