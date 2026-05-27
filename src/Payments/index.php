<?php

require_once "PaymentStrategy.php";
require_once "VisaPayment.php";
require_once "EWalletPayment.php";
require_once "InstaPayPayment.php";
require_once "PaymentProcessor.php";
require_once "PaymentController.php";

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
?>

<!DOCTYPE html>
<html>
<body>

<form method="POST" action="index.php">

    <input type="number" name="amount" placeholder="Enter amount" min="1" required>

    <select name="payment_method" id="payment_method" required>
        <option value="visa">Visa</option>
        <option value="ewallet">E-Wallet</option>
        <option value="instapay">InstaPay</option>
    </select>

    <div id="visa_fields">
        <input type="text" name="card_number" placeholder="Card Number (16 digits)">
        <input type="text" name="cvv" placeholder="CVV">
    </div>

    <div id="ewallet_fields" style="display:none;">
        <input type="text" name="wallet_number" placeholder="Wallet Number">
    </div>

    <div id="instapay_fields" style="display:none;">
        <input type="text" name="instapay_address" placeholder="InstaPay Address">
    </div>

    <button type="submit">Pay</button>

</form>

<script>
    const select = document.getElementById('payment_method');
    const allFields = ['visa_fields', 'ewallet_fields', 'instapay_fields'];

    select.addEventListener('change', function () {
        allFields.forEach(id => document.getElementById(id).style.display = 'none');
        document.getElementById(this.value + '_fields').style.display = 'block';
    });
</script>

</body>
</html>