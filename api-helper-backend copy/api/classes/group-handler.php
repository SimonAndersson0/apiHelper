<?php
require_once __DIR__ . '/base-handler.php';

class GroupHandler extends BaseHandler {

    public function listGroups($project_id = null) {
        $sql = "SELECT * FROM endpoint_groups";
        $params = [];
        if ($project_id) { 
            $sql .= " WHERE project_id=:pid"; 
            $params[':pid'] = $project_id; 
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return Response::success("Groups loaded", $stmt->fetchAll());
    }

    public function getGroup($id) {
        $stmt = $this->db->prepare("SELECT * FROM endpoint_groups WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        return Response::success("Group loaded", $stmt->fetch());
    }

    public function createGroup($project_id, $name, $parent_id = null) {
        $stmt = $this->db->prepare("INSERT INTO endpoint_groups (project_id, name, parent_id) VALUES (:pid, :name, :parent)");
        $stmt->execute([':pid'=>$project_id, ':name'=>$name, ':parent'=>$parent_id]);
        return Response::success("Group created", ['id'=>$this->db->lastInsertId()]);
    }

    function editGroup($id, $project_id, $name = null, $parent_id = null) {

        try {
            // Start with required fields
            $fields = ["project_id = :project_id"];
            $params = [
                ':id' => $id,
                ':project_id' => $project_id
            ];

            // Optional fields
            $optionalFields = ['name', 'parent_id'];
            foreach ($optionalFields as $field) {
                if ($field !== null || $field!=='') {
                    $fields[] = "$field = :$field";
                    $params[":$field"] = $$field;
                }
            }

            $sql = "UPDATE endpoint_groups SET " . implode(", ", $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return Response::success("Group updated"); // important!
        }

        catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }

    public function deleteGroup($id) {
        $stmt = $this->db->prepare("DELETE FROM endpoint_groups WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        return Response::success("Group deleted");
    }
}
