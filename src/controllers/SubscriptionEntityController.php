<?php
namespace App\Controllers;

use App\Models\SubscriptionEntity;
use App\Models\SubscriptionAttributeValue;
use App\Models\AttributeDefinition;

class SubscriptionEntityController {
    private \PDO $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function create(int $subscriptionId): int {
        $stmt = $this->db->prepare("INSERT INTO subscription_entities (subscription_id) VALUES (:subscription_id)");
        $stmt->execute([':subscription_id' => $subscriptionId]);
        return (int) $this->db->lastInsertId();
    }

    public function getById(int $entityId) {
        $stmt = $this->db->prepare("SELECT * FROM subscription_entities WHERE entity_id = :id");
        $stmt->bindValue(':id', $entityId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function getAttributes(int $entityId): array {
        $stmt = $this->db->prepare(
            "SELECT sav.value_id, sav.value, ad.attribute_id, ad.name, ad.data_type, ad.allowed_values
             FROM subscription_attribute_values sav
             JOIN attribute_definitions ad ON sav.attribute_id = ad.attribute_id
             WHERE sav.entity_id = :entity_id
             ORDER BY ad.name"
        );
        $stmt->bindValue(':entity_id', $entityId, \PDO::PARAM_INT);
        $stmt->execute();

        $rows = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function setAttribute(int $entityId, int $attributeId, string $value): bool {
        // Verify attribute exists
        $attrStmt = $this->db->prepare("SELECT * FROM attribute_definitions WHERE attribute_id = :id");
        $attrStmt->bindValue(':id', $attributeId, \PDO::PARAM_INT);
        $attrStmt->execute();
        $attribute = $attrStmt->fetch(\PDO::FETCH_ASSOC);

        if (!$attribute) {
            throw new \Exception("Attribute not found");
        }

        // Validate value using AttributeDefinition model
        $def = new AttributeDefinition(
            $attribute['attribute_id'],
            $attribute['name'],
            $attribute['data_type'],
            $attribute['allowed_values']
        );

        if (!$def->isValueAllowed($value)) {
            throw new \Exception("Invalid value for attribute: {$attribute['name']}");
        }

        $stmt = $this->db->prepare(
            "INSERT INTO subscription_attribute_values (entity_id, attribute_id, value)
             VALUES (:entity_id, :attribute_id, :value)
             ON DUPLICATE KEY UPDATE value = :value"
        );

        return $stmt->execute([
            ':entity_id' => $entityId,
            ':attribute_id' => $attributeId,
            ':value' => $value,
        ]);
    }

    /**
     * Remove an attribute
     */
    public function removeAttribute(int $entityId, int $attributeId): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM subscription_attribute_values WHERE entity_id = :entity_id AND attribute_id = :attribute_id"
        );

        return $stmt->execute([
            ':entity_id' => $entityId,
            ':attribute_id' => $attributeId,
        ]);
    }

    /**
     * Delete entity
     */
    public function delete(int $entityId): bool {
        $stmt = $this->db->prepare("DELETE FROM subscription_entities WHERE entity_id = :id");
        return $stmt->execute([':id' => $entityId]);
    }
}
