<?php
require_once __DIR__ . '/BaseHandler.php';
require_once __DIR__ . '/../helpers/Response.php';

class ParameterHandler extends BaseHandler {

    public function createParameter($variation_id, $name, $type, $required = 0, $description = null) {
        try {
            $stmt = $this->db->prepare("INSERT INTO parameters (variation_id, name, type, required, description) VALUES (:variation_id, :name, :type, :required, :desc)");
            $stmt->execute([
                ':variation_id'=>$variation_id,
                ':name'=>$name,
                ':type'=>$type,
                ':required'=>$required,
                ':desc'=>$description
            ]);
            return Response::success("Parameter created", ['id'=>$this->db->lastInsertId()]);
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function editParameter($id, $variation_id = null, $name = null, $type = null, $required = null, $description = null) {
        try {
            $fields = [];
            $params = [':id'=>$id];
            $optionalFields = ['variation_id','name','type','required','description'];
            foreach($optionalFields as $field){
                if($$field !== null){
                    $fields[] = "$field = :$field";
                    $params[":$field"] = $$field;
                }
            }
            if(empty($fields)) return Response::error("No fields to update");
            $sql = "UPDATE parameters SET ".implode(", ", $fields)." WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return Response::success("Parameter updated");
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function deleteParameter($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM parameters WHERE id = :id");
            $stmt->execute([':id'=>$id]);
            return Response::success("Parameter deleted");
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function getParameter($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM parameters WHERE id = :id");
            $stmt->execute([':id'=>$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$data) return Response::error("Parameter not found", [], 404);
            return Response::success("Parameter loaded", $data);
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function listParameters($variation_id = null) {
        try {
            $sql = "SELECT * FROM parameters";
            $params = [];
            if($variation_id !== null){
                $sql .= " WHERE variation_id = :variation_id";
                $params[':variation_id'] = $variation_id;
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return Response::success("Parameters loaded", $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }
}
?>
