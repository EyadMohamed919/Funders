<?php

class VisaPayment implements PaymentStrategy
{
    public function pay(float $amount, array $data)
    {
        $cardNumber = $data['card_number'];
        $cvv = $data['cvv'];

    }
}