<?php
require_once __DIR__ . '/base-handler.php';

class ParameterHandler extends BaseHandler {

    public function listParameters($variation_id = null) {
        $sql = "SELECT * FROM parameters";
        $params = [];
        if ($variation_id) { $sql .= " WHERE variation_id=:vid"; $params[':vid']=$variation_id; }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        Response::success("Parameters loaded", $stmt->fetchAll());
    }

    public function getParameter($id) {
        $stmt = $this->db->prepare("SELECT * FROM parameters WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        Response::success("Parameter loaded", $stmt->fetch());
    }

    public function createParameter($variation_id, $name, $type, $required = 0, $description = null) {
        $stmt = $this->db->prepare("INSERT INTO parameters (variation_id, name, type, required, description) VALUES (:vid, :name, :type, :req, :desc)");
        $stmt->execute([':vid'=>$variation_id, ':name'=>$name, ':type'=>$type, ':req'=>$required, ':desc'=>$description]);
        Response::success("Parameter created", ['id'=>$this->db->lastInsertId()]);
    }

    public function editParameter($id, $variation_id = null, $name = null, $type = null, $required = 0, $description = null) {
        $stmt = $this->db->prepare("UPDATE parameters SET variation_id=:vid, name=:name, type=:type, required=:req, description=:desc WHERE id=:id");
        $stmt->execute([':id'=>$id, ':vid'=>$variation_id, ':name'=>$name, ':type'=>$type, ':req'=>$required, ':desc'=>$description]);
        Response::success("Parameter updated");
    }

    public function deleteParameter($id) {
        $stmt = $this->db->prepare("DELETE FROM parameters WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        Response::success("Parameter deleted");
    }
}
