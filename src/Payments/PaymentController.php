<?php

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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $method = $_POST['payment_method'] ?? '';
        $amount = (float) ($_POST['amount'] ?? 0);
        $attributes = $this->extractAttributes($method);

        $payment = new Payment($method, $amount, $attributes);

        $errors = $payment->validate();
        if ($errors) {
            die(implode("<br>", $errors));
        }

        $this->save($payment);

        echo "<h1>Payment saved successfully!</h1>";
        echo "<a href='index.php'>Back to Home</a>";
    }

    private function extractAttributes(string $method): array
    {
        return match ($method) {
            'visa'=> ['card_number' => $_POST['card_number'] ?? '',
                'cvv'=> $_POST['cvv'] ?? ''
            ],
            'ewallet'=> ['wallet_number' => $_POST['wallet_number'] ?? ''
            ],
            'instapay' => ['instapay_address' => $_POST['instapay_address'] ?? ''
            ],
            default => []
        };
    }

    private function save(Payment $payment)
{
    $method = $this->db->quote($payment->getPaymentMethod());
    $amount = $payment->getAmount();

    $this->db->exec(
        "INSERT INTO payments (payment_method, amount) VALUES ($method, $amount)"
    );

    $paymentId = $this->db->lastInsertId();

    foreach ($payment->getAttributes() as $key => $value) {
        $key = $this->db->quote($key);
        $value = $this->db->quote($value);

        $this->db->exec("INSERT INTO payment_attributes (payment_id, attribute_name, attribute_value) VALUES ($paymentId, $key, $value)");
    }
}
}