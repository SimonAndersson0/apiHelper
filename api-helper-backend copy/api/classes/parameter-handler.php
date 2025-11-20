<?php
// classes/parameters-handler.php
require_once __DIR__ . '/base-handler.php';
require_once __DIR__ . '/../helpers/Response.php';

class ParametersHandler extends BaseHandler {

    public function createParameter($endpoint_id, $name, $type, $required = 0, $description = null) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO parameters (endpoint_id, name, type, required, description) 
                VALUES (:endpoint_id, :name, :type, :required, :description)
            ");
            $stmt->execute([
                ':endpoint_id' => $endpoint_id,
                ':name' => $name,
                ':type' => $type,
                ':required' => $required,
                ':description' => $description
            ]);

            return Response::success("Parameter created", ['id' => $this->db->lastInsertId()]);
        } catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }

    public function editParameter($id, $endpoint_id = null, $name = null, $type = null, $required = null, $description = null) {
        try {
            $fields = [];
            $params = [':id' => $id];

            $optionalFields = ['endpoint_id', 'name', 'type', 'required', 'description'];
            foreach ($optionalFields as $field) {
                if ($$field !== null) {
                    $fields[] = "$field = :$field";
                    $params[":$field"] = $$field;
                }
            }

            if (empty($fields)) {
                return Response::error("No fields to update");
            }

            $sql = "UPDATE parameters SET " . implode(", ", $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return Response::success("Parameter updated");
        } catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }

    public function deleteParameter($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM parameters WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return Response::success("Parameter deleted");
        } catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }

    public function getParametersByEndpoint($endpoint_id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM parameters WHERE endpoint_id = :eid");
            $stmt->execute([':eid' => $endpoint_id]);
            return Response::success("Parameters loaded", $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }
}
