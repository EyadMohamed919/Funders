<?php
namespace App\Models;

class Subscription {


private int $SubscriptionID;
private string $frequency;
private string $status;
private \DateTime $startDate;
private int $creationDate;
private int $nextBillingDate;
private string $gatewayID;
private float $amount;
private SubscriptionEntity $eavEntity;

public function getSubscriptionID(): int {
    return $this->SubscriptionID;
}

public function setSubscriptionID(int $SubscriptionID): void {
    $this->SubscriptionID = $SubscriptionID;
}

public function getFrequency(): string {
    return $this->frequency;


}

public function setFrequency(string $frequency): void {
    $this->frequency = $frequency;
}

public function getStatus(): string {
    return $this->status;

}
public function setStatus(string $status): void {
    $this->status = $status;
}
public function getStartDate(): \DateTime {
    return $this->startDate;
}
public function setStartDate(\DateTime $startDate): void {
    $this->startDate = $startDate;

}
public function getCreationDate(): int {
    return $this->creationDate;
}
public function setCreationDate(int $creationDate): void {
    $this->creationDate = $creationDate;
}
public function getNextBillingDate(): int {
    return $this->nextBillingDate;
}
public function setNextBillingDate(int $nextBillingDate): void {
    $this->nextBillingDate = $nextBillingDate;
}
public function getGatewayID(): string {
    return $this->gatewayID;
}
public function setGatewayID(string $gatewayID): void {
    $this->gatewayID = $gatewayID;
}
public function getAmount(): float {
    return $this->amount;
}
public function setAmount(float $amount): void {
    $this->amount = $amount;
}

public function getEavEntity(): SubscriptionEntity {
    return $this->eavEntity;
}
public function setEavEntity(SubscriptionEntity $eavEntity): void {
    $this->eavEntity = $eavEntity;
}
}