<?php
namespace App\Controllers;

class SubscriptionEntityController
{
    private \mysqli $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    public function getBySubscriptionId(int $subscriptionId): array
    {
        $entities = $this->runSelect(
            'SELECT entity_id, subscription_id, created_at FROM subscription_entities WHERE subscription_id = ? ORDER BY entity_id',
            'i',
            [$subscriptionId]
        );

        if (empty($entities)) {
            return [];
        }

        $entityIds = array_column($entities, 'entity_id');
        $valueRows = $this->runSelect(
            sprintf(
                'SELECT entity_id, attribute_id, value FROM subscription_attribute_values WHERE entity_id IN (%s)',
                implode(',', array_fill(0, count($entityIds), '?'))
            ),
            str_repeat('i', count($entityIds)),
            array_map('intval', $entityIds)
        );

        $attributeIds = array_unique(array_column($valueRows, 'attribute_id'));
        $attributeNames = [];
        if (!empty($attributeIds)) {
            $definitions = $this->runSelect(
                sprintf(
                    'SELECT attribute_id, name FROM attribute_definitions WHERE attribute_id IN (%s)',
                    implode(',', array_fill(0, count($attributeIds), '?'))
                ),
                str_repeat('i', count($attributeIds)),
                array_map('intval', $attributeIds)
            );

            foreach ($definitions as $def) {
                $attributeNames[$def['attribute_id']] = $def['name'];
            }
        }

        $entityValues = [];
        foreach ($valueRows as $valueRow) {
            $entityId = $valueRow['entity_id'];
            $entityValues[$entityId][] = [
                'attribute_id' => $valueRow['attribute_id'],
                'value' => $valueRow['value'],
            ];
        }

        foreach ($entities as &$entity) {
            $attributes = [];
            $values = $entityValues[$entity['entity_id']] ?? [];
            foreach ($values as $valueItem) {
                $attributeName = $attributeNames[$valueItem['attribute_id']] ?? 'attribute_' . $valueItem['attribute_id'];
                $attributes[] = $attributeName . ':' . $valueItem['value'];
            }
            $entity['attributes'] = implode('|', $attributes);
        }
        unset($entity);

        return $entities;
    }

    private function runSelect(string $sql, string $types, array $params): array
    {
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new \RuntimeException('SQL prepare failed: ' . $this->db->error);
        }

        if ($types !== '') {
            $this->bindParams($stmt, $types, $params);
        }

        if (! $stmt->execute()) {
            throw new \RuntimeException('SQL execute failed: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        $rows = [];

        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $result->free();
        }

        $stmt->close();
        return $rows;
    }

    private function bindParams(\mysqli_stmt $stmt, string $types, array $params): void
    {
        if ($params === []) {
            return;
        }

        $refs = [];
        foreach ($params as $key => $value) {
            $refs[$key] = &$params[$key];
        }

        array_unshift($refs, $types);
        call_user_func_array([$stmt, 'bind_param'], $refs);
    }
}
