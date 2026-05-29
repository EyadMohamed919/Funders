<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/PaymentStyles.css">
    <title>Funders | Payment Page</title>
    
</head>
<body>

<div class="payment-container">
    <form method="POST" action="/src/routers/PaymentRouter.php">

    <label>Amount</label>
    <input type="number" step="0.01" name="amount" placeholder="Enter amount" min="1" required>

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