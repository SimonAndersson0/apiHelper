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
        Response::success("Groups loaded", $stmt->fetchAll());
    }

    public function getGroup($id) {
        $stmt = $this->db->prepare("SELECT * FROM endpoint_groups WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        Response::success("Group loaded", $stmt->fetch());
    }

    public function createGroup($project_id, $name, $parent_id = null) {
        $stmt = $this->db->prepare("INSERT INTO endpoint_groups (project_id, name, parent_id) VALUES (:pid, :name, :parent)");
        $stmt->execute([':pid'=>$project_id, ':name'=>$name, ':parent'=>$parent_id]);
        Response::success("Group created", ['id'=>$this->db->lastInsertId()]);
    }

    public function editGroup($id, $project_id = null, $name = null, $parent_id = null) {
        $stmt = $this->db->prepare("UPDATE endpoint_groups SET project_id=:pid, name=:name, parent_id=:parent WHERE id=:id");
        $stmt->execute([':id'=>$id, ':pid'=>$project_id, ':name'=>$name, ':parent'=>$parent_id]);
        Response::success("Group updated");
    }

    public function deleteGroup($id) {
        $stmt = $this->db->prepare("DELETE FROM endpoint_groups WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        Response::success("Group deleted");
    }
}
