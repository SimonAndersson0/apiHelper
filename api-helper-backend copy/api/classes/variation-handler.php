<?php
require_once __DIR__ . '/base-handler.php';

class VariationHandler extends BaseHandler {

    public function listVariations($endpoint_id = null) {
        $sql = "SELECT * FROM variations";
        $params = [];
        if ($endpoint_id) { $sql .= " WHERE endpoint_id=:eid"; $params[':eid']=$endpoint_id; }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        Response::success("Variations loaded", $stmt->fetchAll());
    }

    public function getVariation($id) {
        $stmt = $this->db->prepare("SELECT * FROM variations WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        Response::success("Variation loaded", $stmt->fetch());
    }

    public function createVariation($endpoint_id, $title, $description = null) {
        $stmt = $this->db->prepare("INSERT INTO variations (endpoint_id, title, description) VALUES (:eid, :title, :desc)");
        $stmt->execute([':eid'=>$endpoint_id, ':title'=>$title, ':desc'=>$description]);
        Response::success("Variation created", ['id'=>$this->db->lastInsertId()]);
    }

    public function editVariation($id, $endpoint_id = null, $title = null, $description = null) {
        $stmt = $this->db->prepare("UPDATE variations SET endpoint_id=:eid, title=:title, description=:desc WHERE id=:id");
        $stmt->execute([':id'=>$id, ':eid'=>$endpoint_id, ':title'=>$title, ':desc'=>$description]);
        Response::success("Variation updated");
    }

    public function deleteVariation($id) {
        $stmt = $this->db->prepare("DELETE FROM variations WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        Response::success("Variation deleted");
    }
}
