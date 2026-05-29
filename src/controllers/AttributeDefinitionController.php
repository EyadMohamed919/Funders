<?php
namespace App\Controllers;

use App\Models\AttributeDefinition;

class AttributeDefinitionController {
    private \PDO $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }


    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM attribute_definitions ORDER BY name ASC");

        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }


    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT * FROM attribute_definitions WHERE attribute_id = :id
        ");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get attribute by name
     */
    public function getByName(string $name): ?array {
        $stmt = $this->db->prepare("
            SELECT * FROM attribute_definitions WHERE name = :name
        ");
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Create new attribute definition
     */
    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO attribute_definitions (name, data_type, allowed_values)
            VALUES (:name, :data_type, :allowed_values)
        ");
        
        $stmt->execute([
            ':name' => $data['name'],
            ':data_type' => $data['data_type'],
            ':allowed_values' => $data['allowed_values'] ?? null
        ]);
        
        return (int) $this->db->lastInsertId();
    }

    /**
     * Update attribute definition
     */
    public function update(int $id, array $data): bool {
        $updates = [];
        $params = [':id' => $id];
        
        $allowed = ['name', 'data_type', 'allowed_values'];
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $sql = "UPDATE attribute_definitions SET " . implode(', ', $updates) . " WHERE attribute_id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($params);
    }

    /**
     * Delete attribute definition
     */
    public function delete(int $id): bool {
        // First delete all values associated with this attribute
        $stmt = $this->db->prepare("DELETE FROM subscription_attribute_values WHERE attribute_id = :id");
        $stmt->execute([':id' => $id]);
        
        // Then delete the definition
        $stmt = $this->db->prepare("DELETE FROM attribute_definitions WHERE attribute_id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Get attributes used by a subscription
     */
    public function getUsageStats(int $attributeId): array {
        $stmt = $this->db->prepare("
            SELECT COUNT(DISTINCT entity_id) as usage_count
            FROM subscription_attribute_values 
            WHERE attribute_id = :id
        ");
        $stmt->bindValue(':id', $attributeId, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    public function validateValue(int $attributeId, string $value): bool {
        $attribute = $this->getById($attributeId);
        
        if (!$attribute) {
            return false;
        }
        
        $def = new AttributeDefinition(
            $attribute['attribute_id'],
            $attribute['name'],
            $attribute['data_type'],
            $attribute['allowed_values']
        );
        
        return $def->isValueAllowed($value);
    }
}
