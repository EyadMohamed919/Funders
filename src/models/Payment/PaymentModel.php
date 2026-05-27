<?php

require_once __DIR__ . "/../../../config/db.php";
class Payment
{
    private string $paymentMethod;
    private float $amount;
    private array $attributes;
    private $db;

    public function __construct(string $paymentMethod, float $amount, array $attributes) {
        $this->paymentMethod = $paymentMethod;
        $this->amount = $amount;
        $this->attributes = $attributes;
        $this->db = getDatabaseConnection();
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function save()
    {
        $method = $this->getPaymentMethod();
        $amount = (float) $this->getAmount();

        $this->db->query("INSERT INTO payments (payment_method, amount) VALUES ('$method', $amount)");

        $paymentId = $this->db->insert_id;

        foreach ($this->getAttributes() as $key => $value) {
            $this->db->query("INSERT INTO payment_attributes (payment_id, attribute_name, attribute_value) 
                            VALUES ($paymentId, '$key', '$value')");
        }

        return $paymentId;
    }

    public function validate(): array
    {
        $errors = [];

        if ($this->amount <= 0) {
            $errors[] = "Amount must be greater than zero.";
        }

        switch ($this->paymentMethod) {

            case 'visa':
                if (!isset($this->attributes['card_number']) || $this->attributes['card_number'] == "" || strlen($this->attributes['card_number']) != 16) {
                    $errors[] = "Card number must be 16 digits.";
                }
                if (!isset($this->attributes['cvv']) || $this->attributes['cvv'] == "" || strlen($this->attributes['cvv']) < 3) {
                    $errors[] = "CVV must be at least 3 digits.";
                }
                break;

            case 'ewallet':
                if (!isset($this->attributes['wallet_number']) || $this->attributes['wallet_number'] == "") {
                    $errors[] = "Wallet number is required.";
                }
                break;

            case 'instapay':
                if (!isset($this->attributes['instapay_address']) || $this->attributes['instapay_address'] == "") {
                    $errors[] = "InstaPay address is required.";
                }
                break;

            default:
                $errors[] = "Invalid payment method.";
        }

        return $errors;
    }
}