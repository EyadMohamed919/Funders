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

    public function createForSubscription(int $subscriptionId): int
{
    $stmt = $this->db->prepare(
        'INSERT INTO subscription_entities (subscription_id, created_at) VALUES (?, NOW())'
    );
    if ($stmt === false) {
        throw new \RuntimeException('Prepare failed: ' . $this->db->error);
    }
    $stmt->bind_param('i', $subscriptionId);
    if (!$stmt->execute()) {
        throw new \RuntimeException('Execute failed: ' . $stmt->error);
    }
    $id = (int) $this->db->insert_id;
    $stmt->close();
    return $id;
}

public function saveAttributeValue(int $entityId, int $attributeId, string $value): void
{
    $stmt = $this->db->prepare(
        'INSERT INTO subscription_attribute_values (entity_id, attribute_id, value, created_at)
         VALUES (?, ?, ?, NOW())'
    );
    if ($stmt === false) {
        throw new \RuntimeException('Prepare failed: ' . $this->db->error);
    }
    $stmt->bind_param('iis', $entityId, $attributeId, $value);
    if (!$stmt->execute()) {
        throw new \RuntimeException('Execute failed: ' . $stmt->error);
    }
    $stmt->close();
}


public function getChoicesBySubscriptionIds(array $subscriptionIds): array
{
    if (empty($subscriptionIds)) return [];

    $ids = array_map('intval', $subscriptionIds);
    $ph  = implode(',', array_fill(0, count($ids), '?'));

    // Step 1: subscription_id -> entity_id
    $entities = $this->runSelect(
        "SELECT entity_id, subscription_id FROM subscription_entities WHERE subscription_id IN ($ph)",
        str_repeat('i', count($ids)),
        $ids
    );
    if (empty($entities)) return [];

    $entityToSub = array_column($entities, 'subscription_id', 'entity_id');
    $entityIds   = array_keys($entityToSub);
    $eph         = implode(',', array_fill(0, count($entityIds), '?'));

    // Step 2: look up attribute_id for 'choice'
    $def = $this->runSelect(
        "SELECT attribute_id FROM attribute_definitions WHERE name = 'choice'",
        '', []
    );
    if (empty($def)) return [];
    $choiceAttrId = (int) $def[0]['attribute_id'];

    // Step 3: get values
    $values = $this->runSelect(
        "SELECT entity_id, value FROM subscription_attribute_values
         WHERE entity_id IN ($eph) AND attribute_id = ?",
        str_repeat('i', count($entityIds)) . 'i',
        [...array_map('intval', $entityIds), $choiceAttrId]
    );

    // Step 4: map subscription_id -> choice
    $result = [];
    foreach ($values as $row) {
        $subId = $entityToSub[$row['entity_id']] ?? null;
        if ($subId !== null) {
            $result[(int)$subId] = $row['value'];
        }
    }
    return $result;
}

}
