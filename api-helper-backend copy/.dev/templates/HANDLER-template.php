<?php
require_once __DIR__ . "/BaseHandler.php";

class ExampleHandler extends BaseHandler
{
    private string $table = "example_table";

    // ===============================
    // CREATE
    // ===============================
    public function create($data)
    {
        try {
            // Required parameters
            $required = ["field1", "field2"];
            foreach ($required as $param) {
                if (!isset($data[$param])) {
                    return Response::error("Missing required parameter: $param");
                }
            }

            $sql = "INSERT INTO {$this->table} (field1, field2, optional_field)
                    VALUES (:field1, :field2, :optional_field)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':field1' => $data['field1'],
                ':field2' => $data['field2'],
                ':optional_field' => $data['optional_field'] ?? null
            ]);

            return Response::success("Created successfully", [
                "id" => $this->db->lastInsertId()
            ]);

        } catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }

    // ===============================
    // GET ONE
    // ===============================
    public function getOne($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $stmt->execute([':id' => $id]);

            $row = $stmt->fetch();

            if (!$row) {
                return Response::error("Item not found");
            }

            return Response::success("Loaded", $row);

        } catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }

    // ===============================
    // LIST
    // ===============================
    public function getList($filter = [])
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE 1=1";
            $params = [];

            // Example optional filters
            if (!empty($filter['project_id'])) {
                $sql .= " AND project_id = :project_id";
                $params[':project_id'] = $filter['project_id'];
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return Response::success("List loaded", $stmt->fetchAll());

        } catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }

    // ===============================
    // EDIT (dynamic fields)
    // ===============================
    public function edit($id, $data)
    {
        try {
            // Required params for editing
            $required = ['project_id']; // example
            foreach ($required as $param) {
                if (!isset($data[$param])) {
                    return Response::error("Missing required parameter: $param");
                }
            }

            // Optional fields
            $optional = ['name', 'description', 'parent_id'];

            $fields = ["project_id = :project_id"];
            $params = [
                ':id' => $id,
                ':project_id' => $data['project_id']
            ];

            foreach ($optional as $field) {
                if (array_key_exists($field, $data) && $data[$field] !== null) {
                    $fields[] = "$field = :$field";
                    $params[":$field"] = $data[$field];
                }
            }

            $sql = "UPDATE {$this->table} SET " . implode(", ", $fields) . " WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return Response::success("Updated successfully");

        } catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }

    // ===============================
    // DELETE
    // ===============================
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $stmt->execute([':id' => $id]);

            return Response::success("Deleted successfully");

        } catch (PDOException $e) {
            return Response::error("Database error: " . $e->getMessage());
        }
    }
}
