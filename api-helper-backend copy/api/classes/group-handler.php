<?php
require_once __DIR__ . '/BaseHandler.php';
require_once __DIR__ . '/../helpers/Response.php';

class GroupHandler extends BaseHandler {

    public function createGroup($project_id, $name, $parent_id = null) {
        try {
            $stmt = $this->db->prepare("INSERT INTO endpoint_groups (project_id, name, parent_id) VALUES (:project_id, :name, :parent_id)");
            $stmt->execute([':project_id'=>$project_id, ':name'=>$name, ':parent_id'=>$parent_id]);
            return Response::success("Group created", ['id'=>$this->db->lastInsertId()]);
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function editGroup($id, $project_id, $name = null, $parent_id = null) {
        try {
            $fields = ['project_id = :project_id'];
            $params = [':id'=>$id, ':project_id'=>$project_id];
            $optionalFields = ['name','parent_id'];
            foreach($optionalFields as $field){
                if($$field !== null){
                    $fields[] = "$field = :$field";
                    $params[":$field"] = $$field;
                }
            }
            $sql = "UPDATE endpoint_groups SET ".implode(", ", $fields)." WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return Response::success("Group updated");
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function deleteGroup($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM endpoint_groups WHERE id = :id");
            $stmt->execute([':id'=>$id]);
            return Response::success("Group deleted");
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function getGroup($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM endpoint_groups WHERE id = :id");
            $stmt->execute([':id'=>$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$data) return Response::error("Group not found", [], 404);
            return Response::success("Group loaded", $data);
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function listGroups($project_id = null) {
        try {
            $sql = "SELECT * FROM endpoint_groups";
            $params = [];
            if($project_id !== null){
                $sql .= " WHERE project_id = :project_id";
                $params[':project_id'] = $project_id;
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return Response::success("Groups loaded", $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }
}
?>
