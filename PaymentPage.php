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
</div>

<script>
    const select = document.getElementById('payment_method');
    const allFields = ['visa_fields', 'ewallet_fields', 'instapay_fields'];

    select.addEventListener('change', function () {
        allFields.forEach(id => document.getElementById(id).style.display = 'none');
        // Fixed JavaScript bug: changed 'block' to 'flex' to align with the new CSS layouts
        document.getElementById(this.value + '_fields').style.display = 'flex'; 
    });
</script>

</body>
</html>