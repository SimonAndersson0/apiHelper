<?php
require_once __DIR__ . '/base-handler.php';

class EndpointHandler extends BaseHandler {

    public function listEndpoints($group_id = null) {
        $sql = "SELECT * FROM endpoints";
        $params = [];
        if ($group_id) { $sql .= " WHERE group_id=:gid"; $params[':gid']=$group_id; }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        Response::success("Endpoints loaded", $stmt->fetchAll());
    }

    public function getEndpoint($id) {
        $stmt = $this->db->prepare("SELECT * FROM endpoints WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        Response::success("Endpoint loaded", $stmt->fetch());
    }

    public function createEndpoint($group_id, $title, $url, $method, $description = null) {
        $stmt = $this->db->prepare("INSERT INTO endpoints (group_id, title, url, method, description) VALUES (:gid, :title, :url, :method, :desc)");
        $stmt->execute([':gid'=>$group_id, ':title'=>$title, ':url'=>$url, ':method'=>strtoupper($method), ':desc'=>$description]);
        Response::success("Endpoint created", ['id'=>$this->db->lastInsertId()]);
    }

    public function editEndpoint($id, $group_id = null, $title = null, $url = null, $method = null, $description = null) {
        $stmt = $this->db->prepare("UPDATE endpoints SET group_id=:gid, title=:title, url=:url, method=:method, description=:desc WHERE id=:id");
        $stmt->execute([':id'=>$id, ':gid'=>$group_id, ':title'=>$title, ':url'=>$url, ':method'=>strtoupper($method ?? 'GET'), ':desc'=>$description]);
        Response::success("Endpoint updated");
    }

    public function deleteEndpoint($id) {
        $stmt = $this->db->prepare("DELETE FROM endpoints WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        Response::success("Endpoint deleted");
    }
}
