<?php

class VisaPayment implements PaymentStrategy
{
    public function pay(float $amount, array $data)
    {
        $cardNumber = $data['card_number'];
        $cvv = $data['cvv'];

        echo "Paid $amount using Visa<br>";
        echo "Card Number: $cardNumber<br>";
        echo "CVV: $cvv";
    }
}