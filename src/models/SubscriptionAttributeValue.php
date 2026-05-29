<?php
namespace App\Models;

class SubscriptionAttributeValue {

    private int $valueID;

    private SubscriptionEntity $entity;

    private AttributeDefinition $attributeDefinition;

    private string $value;

    public function __construct(int $valueID = 0, ?SubscriptionEntity $entity = null, ?AttributeDefinition $def = null, string $value = '') {
        if ($entity !== null) $this->entity = $entity;
        if ($def !== null) $this->attributeDefinition = $def;
        $this->valueID = $valueID;
        $this->value = $value;
    }

    public function getValueID(): int { return $this->valueID; }
    public function setValueID(int $id): void { $this->valueID = $id; }

    public function getEntity(): SubscriptionEntity { return $this->entity; }
    public function setEntity(SubscriptionEntity $entity): void { $this->entity = $entity; }

    public function getAttributeDefinition(): AttributeDefinition { return $this->attributeDefinition; }
    public function setAttributeDefinition(AttributeDefinition $def): void { $this->attributeDefinition = $def; }

    public function getValue(): string { return $this->value; }
    public function setValue(string $value): void { $this->value = $value; }
}
