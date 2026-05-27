<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate Physical Goods</title>
    <style>
        /* Base Layout Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            color: #333333;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }

        /* Card Container */
        .donation-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            max-width: 500px;
            width: 100%;
            padding: 35px;
            box-sizing: border-box;
            border-top: 6px solid #2d6a4f; /* Accent green strip */
        }

        /* Form Header */
        .form-header {
            margin-bottom: 25px;
            text-align: center;
        }

        .form-header h2 {
            margin: 0 0 10px 0;
            color: #2d6a4f;
            font-size: 24px;
        }

        .form-header p {
            margin: 0;
            color: #666666;
            font-size: 14px;
            line-height: 1.4;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #444444;
        }

        /* Inputs, Textareas, and Suffix Wrappers */
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 12px;
            font-size: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            background-color: #fafafa;
            color: #333333;
            box-sizing: border-box;
            font-family: inherit;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        /* Padding adjustment for weight input to accommodate 'kg' suffix */
        input[name="weight"] {
            padding-right: 45px;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Input Focus States */
        input:focus,
        textarea:focus {
            outline: none;
            border-color: #2d6a4f;
            background-color: #ffffff;
        }

        /* Weight Unit Suffix (KG) */
        .weight-suffix {
            position: absolute;
            right: 15px;
            font-size: 14px;
            color: #888888;
            font-weight: bold;
            pointer-events: none; /* Prevents blocking clicks on input */
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            background-color: #2d6a4f;
            color: #ffffff;
            border: none;
            padding: 14px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background-color: #1b5e20;
        }

        /* Subtle Help Text */
        .help-text {
            font-size: 12px;
            color: #888888;
            margin-top: 4px;
        }
    </style>
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
                placeholder="e.g., Winter Jackets, Non-perishable Canned Food" 
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
                    placeholder="e.g., 12.5" 
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
                placeholder="Describe the items, quantities, sizes, conditions, or any special handling/storage requirements..." 
                required
            ></textarea>
        </div>

        <button name="goodsDonation" type="submit" class="submit-btn">Submit Donation Details</button>

    </form>

</div>

</body>
</html>