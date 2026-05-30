<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/public/css/GoodsDonationStyle.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funders: Help with Service</title>
</head>
<body>

<div class="donation-card">
    
    <div class="form-header">
        <h2>Service</h2>
        <p>Provide a service that can help with the campaign</p>
    </div>

    <form method="POST" action="/src/routers/DonationRouter.php">

        <div class="form-group">
            <label for="goodName">Service Name</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                placeholder="car ride"
                value="Car ride"
                required
            >
        </div>

        <div class="form-group">
            <label for="goodName">Your contact number (will be sent to the donee)</label>
            <input 
                type="text" 
                id="contact" 
                name="contact" 
                placeholder="+201150790985";
                value="+201150790985" 
                required
            >
        </div>

        <button name="serviceDonation" type="submit" class="submit-btn">Submit Donation Details</button>

    </form>

</div>

</body>
</html>