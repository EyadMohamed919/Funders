<?php

class EWalletPayment implements PaymentStrategy
{
    public function pay(float $amount, array $data)
    {
        $walletNumber = $data['wallet_number'];

        echo "Paid $amount using E-Wallet";
        echo "Wallet Number: $walletNumber";
    }
}