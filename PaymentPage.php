<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <style>
        /* Base styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f9f4; /* Soft, light green-tinted background */
            color: #2b4c2b; /* Deep forest green for text */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Form container */
        .payment-container {
            background-color: #ffffff; /* Crisp white background */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(43, 76, 43, 0.1); /* Subtle green-tinted shadow */
            width: 100%;
            max-width: 400px;
        }

        /* Form layout */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px; /* Spaces out the inputs evenly */
        }

        /* Inputs and Select dropdowns */
        input[type="number"],
        input[type="text"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #a2cfa2; /* Soft green border */
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            color: #2b4c2b;
            background-color: #ffffff;
            transition: border-color 0.3s ease;
        }

        /* Highlight input on click/focus */
        input:focus,
        select:focus {
            outline: none;
            border-color: #2e7d32; /* Stronger green when active */
        }

        /* Dynamic field groups wrapper */
        #visa_fields, 
        #ewallet_fields, 
        #instapay_fields {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* The Submit Button */
        button[type="submit"] {
            background-color: #2e7d32; /* Primary Green */
            color: #ffffff;
            border: none;
            padding: 14px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin-top: 10px;
        }

        /* Button hover effect */
        button[type="submit"]:hover {
            background-color: #1b5e20; /* Darker green on hover */
        }
    </style>
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