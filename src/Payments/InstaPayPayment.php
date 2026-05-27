<?php

class InstaPayPayment implements PaymentStrategy
{
    public function pay(float $amount, array $data)
    {
        $instapayAddress = $data['instapay_address'];

        echo "Paid $amount using InstaPay";
        echo "InstaPay Address: $instapayAddress";
    }
}