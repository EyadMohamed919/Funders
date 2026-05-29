<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "Database.php";
require_once "PaymentModel.php";

class PaymentController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function store()
    {
        $method = $_POST['payment_method'] ?? '';
        $amount = (float) ($_POST['amount'] ?? 0);
        $attributes = $this->extractAttributes($method);

        $payment = new Payment($method, $amount, $attributes);

        $errors = $payment->validate();
        if ($errors) {
            die(implode("<br>", $errors));
        }

        $this->save($payment);
    }

    private function extractAttributes(string $method): array
    {
        return match ($method) {
            'visa'=> ['card_number' => $_POST['card_number'], 'cvv' => $_POST['cvv']],
            'ewallet'=> ['wallet_number' => $_POST['wallet_number']],
            'instapay'=> ['instapay_address' => $_POST['instapay_address']],
            default => []
        };
    }

    private function save(Payment $payment)
    {
        $method = $this->db->quote($payment->getPaymentMethod());
        $amount = $payment->getAmount();
        $userId = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : 'NULL';

        $this->db->exec("INSERT INTO payments (payment_method, amount, user_id) VALUES ($method, $amount, $userId)");

        $paymentId = $this->db->lastInsertId();

        foreach ($payment->getAttributes() as $key => $value) {
            $key = $this->db->quote($key);
            $value = $this->db->quote($value);

            $this->db->exec("INSERT INTO payment_attributes (payment_id, attribute_name, attribute_value) VALUES ($paymentId, $key, $value)");
        }
    }
}