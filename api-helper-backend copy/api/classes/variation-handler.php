<?php
// classes/variations-handler.php
require_once __DIR__ . '/base-handler.php';
require_once __DIR__ . '/../helpers/Response.php';

class VariationsHandler extends BaseHandler {

    public function createVariation($endpoint_id, $title, $description = null, $parameter_values = []) {
        try {
            // Create variation
            $stmt = $this->db->prepare("
                INSERT INTO variations (endpoint_id, title, description) 
                VALUES (:endpoint_id, :title, :description)
            ");
            $stmt->execute([
                ':endpoint_id' => $endpoint_id,
                ':title' => $title,
                ':description' => $description
            ]);

            $variation_id = $this->db->lastInsertId();

            // Insert variation parameter values if provided
            foreach ($parameter_values as $param_id => $valueData) {
                $stmt2 = $this->db->prepare("
                    INSERT INTO variation_parameters (variation_id, parameter_id, value, value_type)
                    VALUES (:vid, :pid, :value, :value_type)
                ");
                $stmt2->execute([
                    ':vid' => $variation_id,
                    ':pid' => $param_id,
                    ':value' => $valueData['value'] ?? '',
                    ':value_type' => $valueData['type'] ?? 'string'
                ]);
            }

            return Response::success("Variation created", ['id' => $variation_id]);

        } catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }

    public function editVariation($id, $title = null, $description = null, $parameter_values = []) {
        try {
            $fields = [];
            $params = [':id' => $id];

            $optionalFields = ['title', 'description'];
            foreach ($optionalFields as $field) {
                if ($$field !== null) {
                    $fields[] = "$field = :$field";
                    $params[":$field"] = $$field;
                }
            }

            if (!empty($fields)) {
                $sql = "UPDATE variations SET " . implode(", ", $fields) . " WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
            }

            // Update variation parameter values
            foreach ($parameter_values as $param_id => $valueData) {
                $stmt2 = $this->db->prepare("
                    UPDATE variation_parameters
                    SET value = :value, value_type = :value_type
                    WHERE variation_id = :vid AND parameter_id = :pid
                ");
                $stmt2->execute([
                    ':vid' => $id,
                    ':pid' => $param_id,
                    ':value' => $valueData['value'] ?? '',
                    ':value_type' => $valueData['type'] ?? 'string'
                ]);
            }

            return Response::success("Variation updated");

        } catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }

    public function deleteVariation($id) {
        try {
            // Delete variation parameters first
            $stmt = $this->db->prepare("DELETE FROM variation_parameters WHERE variation_id = :vid");
            $stmt->execute([':vid' => $id]);

            // Delete variation
            $stmt = $this->db->prepare("DELETE FROM variations WHERE id = :id");
            $stmt->execute([':id' => $id]);

            return Response::success("Variation deleted");
        } catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }

    public function getVariationsByEndpoint($endpoint_id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM variations WHERE endpoint_id = :eid");
            $stmt->execute([':eid' => $endpoint_id]);
            $variations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Include parameter values
            foreach ($variations as &$var) {
                $stmt2 = $this->db->prepare("
                    SELECT vp.*, p.name
                    FROM variation_parameters vp
                    JOIN parameters p ON vp.parameter_id = p.id
                    WHERE vp.variation_id = :vid
                ");
                $stmt2->execute([':vid' => $var['id']]);
                $var['parameters'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            }

            return Response::success("Variations loaded", $variations);

        } catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }
}
