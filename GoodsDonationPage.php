<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/public/css/GoodsDonationStyle.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate Physical Goods</title>
</head>
<body>

<div class="donation-card">
    
    <div class="form-header">
        <h2>Donate Material Goods</h2>
        <p>Fill out the details below to register your items. Your contribution directly supports community distribution networks.</p>
    </div>

    <form method="POST" action="/src/routers/DonationRouter.php">

        <div class="form-group">
            <label for="goodName">Item Name / Title</label>
            <input 
                type="text" 
                id="goodName" 
                name="goodsName" 
                placeholder="jacket" 
                required
            >
        </div>

        <div class="form-group">
            <label for="weight">Estimated Total Weight</label>
            <div class="input-wrapper">
                <input 
                    type="number" 
                    id="weight" 
                    name="weight" 
                    placeholder="1.5" 
                    step="0.01" 
                    min="0.01" 
                    required
                >
                <span class="weight-suffix">kg</span>
            </div>
            <span class="help-text">Please provide an approximate weight to help us arrange logistics.</span>
        </div>

        <div class="form-group">
            <label for="goodsDetails">Item Details & Condition</label>
            <textarea 
                id="goodsDetails" 
                name="goodsDetails" 
                placeholder="Describe the good" 
                required
            ></textarea>
        </div>

        <button name="goodsDonation" type="submit" class="submit-btn">Submit Donation Details</button>

    </form>

</div>

</body>
</html>