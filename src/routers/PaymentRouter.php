<?php
require_once __DIR__ . "/../models/Payment/PaymentStartegy.php";
require_once __DIR__ . "/../models/Payment/VisaPayment.php";
require_once __DIR__ . "/../models/Payment/EWalletPayment.php";
require_once __DIR__ . "/../models/Payment/InstaPayPayment.php";
require_once __DIR__ . "/../models/Payment/PaymentProcessor.php";
require_once __DIR__ . "/../controllers/PaymentController.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $paymentMethod = $_POST['payment_method'];
    $amount = (float) $_POST['amount'];

    if ($paymentMethod == "visa") {
        $strategy = new VisaPayment();
        $data = [
            'card_number' => $_POST['card_number'] ?? '',
            'cvv'=> $_POST['cvv']?? ''
        ];
    } elseif ($paymentMethod == "ewallet") {
        $strategy = new EWalletPayment();
        $data = [
            'wallet_number' => $_POST['wallet_number'] ?? ''
        ];
    } elseif ($paymentMethod == "instapay") {
        $strategy = new InstaPayPayment();
        $data = [
            'instapay_address' => $_POST['instapay_address'] ?? ''
        ];
    } else {
        die("Invalid payment method");
    }

    $processor = new PaymentProcessor($strategy);
    $processor->checkout($amount, $data);

    $controller = new PaymentController();
    $controller->store();
}