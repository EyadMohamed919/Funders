<?php

class Payment
{
    public string $paymentMethod;
    public float $amount;
    public array $data;

    public function __construct(string $paymentMethod, float $amount, array $data)
    {
        $this->paymentMethod = $paymentMethod;
        $this->amount = $amount;
        $this->data = $data;
    }

    public function getPaymentMethod() {
        return $this->paymentMethod;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getData() {
        return $this->data;
    }

    public function validate()
    {
        $errors = array();

        if ($this->amount <= 0) {
            $errors[] = "Amount must be greater than zero.";
        }

        switch ($this->paymentMethod) {
            case 'visa':
                if (empty($this->data['card_number']) || strlen($this->data['card_number']) != 16) {
                    $errors[] = "Card number must be 16 digits.";
                }
                if (empty($this->data['cvv']) || strlen($this->data['cvv']) < 3) {
                    $errors[] = "CVV must be at least 3 digits.";
                }
                break;

            case 'ewallet':
                if (empty($this->data['wallet_number'])) {
                    $errors[] = "Wallet number is required.";
                }
                break;

            case 'instapay':
                if (empty($this->data['instapay_address'])) {
                    $errors[] = "InstaPay address is required.";
                }
                break;

            default:
                $errors[] = "Invalid payment method.";
                break;
        }

        return $errors;
    }

    public function toArray()
    {
        return [
            'payment_method' => $this->paymentMethod,
            'amount' => $this->amount,
            'data' => $this->data
        ];
    }
}