<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "PaymentStrategy.php";
require_once "VisaPayment.php";
require_once "EWalletPayment.php";
require_once "InstaPayPayment.php";
require_once "PaymentProcessor.php";
require_once "PaymentController.php";

$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $paymentMethod = $_POST['payment_method'];
    $amount = (float) $_POST['amount'];

    if ($paymentMethod == "visa") {
        $strategy = new VisaPayment();
        $data = [
            'card_number' => $_POST['card_number'] ?? '',
            'cvv'         => $_POST['cvv']         ?? ''
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

    $success = true;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card">

<?php if ($success): ?>

    <div class="success-card">
        <h2>Payment Successful!</h2>
        <a href="index.php"><button type="button">Back to Home</button></a>
    </div>

<?php else: ?>

    <h2>Make a Payment</h2>

    <form method="POST" action="index.php">

        <label>Amount</label>
        <input type="text" name="amount" placeholder="Enter amount" min="1" required>

        <label>Payment Method</label>
        <select name="payment_method" id="payment_method" required>
            <option value="visa">Visa</option>
            <option value="ewallet">E-Wallet</option>
            <option value="instapay">InstaPay</option>
        </select>

        <hr class="divider">

        <div id="visa">
            <label>Card Number</label>
            <input type="text" name="card_number" placeholder="16-digit card number">
            <label>CVV</label>
            <input type="text" name="cvv" placeholder="CVV">
        </div>

        <div id="ewallet" style="display:none;">
            <label>Wallet Number</label>
            <input type="text" name="wallet_number" placeholder="Wallet Number">
        </div>

        <div id="instapay" style="display:none;">
            <label>InstaPay Address</label>
            <input type="text" name="instapay_address" placeholder="InstaPay Address">
        </div>

        <button type="submit">Pay Now</button>

    </form>

<?php endif; ?>

</div>

<script>
    const select = document.getElementById('payment_method');
    const allFields = ['visa', 'ewallet', 'instapay'];

    // hide all except visa on page load
    document.getElementById('visa').style.display = 'block';
    document.getElementById('ewallet').style.display = 'none';
    document.getElementById('instapay').style.display = 'none';


    select.addEventListener('change', function () {
        allFields.forEach(id => document.getElementById(id).style.display = 'none');
        document.getElementById(this.value).style.display = 'block';
});
</script>

</body>
</html>