<?php

require_once __DIR__ . "/../models/Payment/PaymentModel.php";
require_once __DIR__ . "/DonationController.php";
class PaymentController
{

    public function __construct()
    {
        // $this->db = Database::connect();
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

        $paymentID = $payment->save();

        // Delwa2ty el payment is saved to database
        // Now we have to proceed to save to donation details
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $donationData = $_SESSION["DonationData"];
        array_push($donationData, $paymentID);
        $_SESSION["DonationData"] = $donationData;
        DonationController::createDonation();
    }

    private function extractAttributes(string $method): array
    {
        return match ($method) {
            'visa'=> ['card_number' => $_POST['card_number'],'cvv'=> $_POST['cvv']],
            'ewallet'=> ['wallet_number' => $_POST['wallet_number']],
            'instapay' => ['instapay_address' => $_POST['instapay_address']],
            default => []
        };
    }

    
}