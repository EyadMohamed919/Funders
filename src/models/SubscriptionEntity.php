<?php
namespace App\Models;


class SubscriptionEntity {

    private int $entityID;
   
    private Subscription $subscription;

    private array $attributeValues = [];

    public function __construct(Subscription $subscription, int $entityID = 0) {
        $this->subscription = $subscription;
        $this->entityID = $entityID;
    }

    public function addAttributeValue(SubscriptionAttributeValue $value): void {
        $value->setEntity($this);
        $this->attributeValues[] = $value;
    }

    public function removeAttributeByName(string $name): void {
        $this->attributeValues = array_values(array_filter($this->attributeValues, function($v) use ($name) {
            return $v->getAttributeDefinition()->getName() !== $name;
        }));
    }

   
    public function getAttribute(string $name): ?string {
        foreach ($this->attributeValues as $v) {
            if ($v->getAttributeDefinition()->getName() === $name) {
                return $v->getValue();
            }
        }
        return null;
    }

    // Getters & setters
    public function getEntityID(): int { return $this->entityID; }
    public function setEntityID(int $id): void { $this->entityID = $id; }

    public function getSubscription(): Subscription { return $this->subscription; }
    public function setSubscription(Subscription $s): void { $this->subscription = $s; }

    public function getAttributeValues(): array { return $this->attributeValues; }
    public function setAttributeValues(array $values): void { $this->attributeValues = $values; }
}
