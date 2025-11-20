<?php
require_once __DIR__ . '/BaseHandler.php';
require_once __DIR__ . '/../helpers/Response.php';

class VariationHandler extends BaseHandler {

    public function createVariation($endpoint_id, $title, $description = null) {
        try {
            $stmt = $this->db->prepare("INSERT INTO variations (endpoint_id, title, description) VALUES (:endpoint_id, :title, :desc)");
            $stmt->execute([':endpoint_id'=>$endpoint_id, ':title'=>$title, ':desc'=>$description]);
            return Response::success("Variation created", ['id'=>$this->db->lastInsertId()]);
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function editVariation($id, $endpoint_id = null, $title = null, $description = null) {
        try {
            $fields = [];
            $params = [':id'=>$id];
            $optionalFields = ['endpoint_id','title','description'];
            foreach($optionalFields as $field){
                if($$field !== null){
                    $fields[] = "$field = :$field";
                    $params[":$field"] = $$field;
                }
            }
            if(empty($fields)) return Response::error("No fields to update");
            $sql = "UPDATE variations SET ".implode(", ", $fields)." WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return Response::success("Variation updated");
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function deleteVariation($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM variations WHERE id = :id");
            $stmt->execute([':id'=>$id]);
            return Response::success("Variation deleted");
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function getVariation($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM variations WHERE id = :id");
            $stmt->execute([':id'=>$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$data) return Response::error("Variation not found", [], 404);
            return Response::success("Variation loaded", $data);
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function listVariations($endpoint_id = null) {
        try {
            $sql = "SELECT * FROM variations";
            $params = [];
            if($endpoint_id !== null){
                $sql .= " WHERE endpoint_id = :endpoint_id";
                $params[':endpoint_id'] = $endpoint_id;
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return Response::success("Variations loaded", $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }
}
?>
