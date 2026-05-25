<?php

require_once "Sub_Startegy.php";
require_once "VisaPayment.php";
require_once "EWalletPayment.php";
require_once "InstaPayPayment.php";
require_once "PaymentProcessor.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $paymentMethod = $_POST['payment_method'];
    $amount = (float) $_POST['amount'];

    if ($paymentMethod == "visa") {
        $strategy = new VisaPayment();
        $data = [
            'card_number' => $_POST['card_number'],
            'cvv'=> $_POST['cvv']];

    } elseif ($paymentMethod == "ewallet") {
        $strategy = new EWalletPayment();
        $data = ['wallet_number' => $_POST['wallet_number']];
    } elseif ($paymentMethod == "instapay") {
        $strategy = new InstaPayPayment();
        $data = ['instapay_address' => $_POST['instapay_address']];
    } else {
        die("Invalid payment method");
    }

    $processor = new PaymentProcessor($strategy);
    $processor->checkout($amount, $data);
}

?>
<html>
<form method="POST" action="index.php">

    <input type="number" name="amount" placeholder="Enter amount" required>

    <select name="payment_method" required>
        <option value="visa">Visa</option>
        <option value="ewallet">E-Wallet</option>
        <option value="instapay">InstaPay</option>
    </select>

    <button type="submit">Pay</button>

</form>
</html>