<?php

class PaymentProcessor
{
    private PaymentStrategy $strategy;

    public function __construct(PaymentStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function checkout(float $amount, array $data)
    {
        $this->strategy->pay($amount, $data);
    }
}