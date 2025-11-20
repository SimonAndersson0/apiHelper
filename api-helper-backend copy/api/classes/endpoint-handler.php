<?php
require_once __DIR__ . '/BaseHandler.php';
require_once __DIR__ . '/../helpers/Response.php';

class EndpointHandler extends BaseHandler {

    public function createEndpoint($group_id, $title, $url, $method, $description = null) {
        try {
            $stmt = $this->db->prepare("INSERT INTO endpoints (group_id, title, url, method, description) VALUES (:group_id, :title, :url, :method, :desc)");
            $stmt->execute([
                ':group_id'=>$group_id,
                ':title'=>$title,
                ':url'=>$url,
                ':method'=>strtoupper($method),
                ':desc'=>$description
            ]);
            return Response::success("Endpoint created", ['id'=>$this->db->lastInsertId()]);
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function editEndpoint($id, $group_id = null, $title = null, $url = null, $method = null, $description = null) {
        try {
            $fields = [];
            $params = [':id'=>$id];
            $optionalFields = ['group_id','title','url','method','description'];
            foreach($optionalFields as $field){
                if($$field !== null){
                    $fields[] = "$field = :$field";
                    $params[":$field"] = ($field==='method') ? strtoupper($$field) : $$field;
                }
            }
            if(empty($fields)) return Response::error("No fields to update");
            $sql = "UPDATE endpoints SET ".implode(", ", $fields)." WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return Response::success("Endpoint updated");
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function deleteEndpoint($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM endpoints WHERE id = :id");
            $stmt->execute([':id'=>$id]);
            return Response::success("Endpoint deleted");
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function getEndpoint($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM endpoints WHERE id = :id");
            $stmt->execute([':id'=>$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$data) return Response::error("Endpoint not found", [], 404);
            return Response::success("Endpoint loaded", $data);
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }

    public function listEndpoints($group_id = null) {
        try {
            $sql = "SELECT * FROM endpoints";
            $params = [];
            if($group_id !== null){
                $sql .= " WHERE group_id = :group_id";
                $params[':group_id'] = $group_id;
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return Response::success("Endpoints loaded", $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return Response::error("Database error: ".$e->getMessage());
        }
    }
}
?>
