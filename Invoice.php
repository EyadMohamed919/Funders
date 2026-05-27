<?php require_once __DIR__ . "/src/controllers/InvoiceController.php"; 
$invoice = new InvoiceController($_GET["donationID"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/InvoiceStyles.css">
    <title>Donation Invoice</title>
</head>
<body>

<div class="invoice-card">
    
    <!-- Header -->
    <div class="invoice-header">
        <div class="brand">
            <h1>Funders Platform</h1>
            <p>Cairo, Egypt</p>
            <p>support@funders.com</p>
        </div>
        <div class="invoice-title">
            <h2>Donation Receipt</h2>
            <p>Receipt #: REC-<?php echo $invoice->donation->getDonationID(); ?></p>
        </div>
    </div>

    <!-- Metadata Grid -->
    <div class="invoice-meta">
        <div class="meta-block">
            <p><strong>Date:</strong> <?php echo $invoice->donation->getCreatedAt(); ?></p>
            <p><strong>Donation Type:</strong> <?php echo $invoice->DonationTypeName; ?></p>
        </div>
        <div class="meta-block" style="text-align: right;">
            <p><strong>Post ID:</strong> #PST-99421</p>
            <p><strong>Donor Status:</strong> Tax-Deductible Gift</p>
        </div>
    </div>

    <!-- Details Table -->
    <table class="invoice-table">
        <thead>
            <tr>
                <th style="width: 70%;">Supported Initiative (Post Title)</th>
                <th style="width: 30%; text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Help Us Plant 10,000 Trees This Month</strong>
                    <div class="donation-details-box">
                        <strong>Allocation Details:</strong><br>
                        This contribution has been allocated toward purchasing indigenous tree saplings, eco-friendly irrigation kits, and supporting local field volunteers for the upcoming June planting cycle.
                    </div>
                </td>
                <td class="text-right amount-cell">$50.00</td>
            </tr>
            <!-- Total Row -->
            <tr>
                <td class="text-right" style="font-weight: bold; padding-top: 20px;">Total Contribution:</td>
                <td class="text-right amount-cell" style="padding-top: 20px;">$50.00</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="invoice-footer">
        <p>Thank you for your generosity! Your support truly makes a difference in our environment.</p>
        <p><em>GreenEarth Foundation is a registered 501(c)(3) organization.</em></p>
    </div>

</div>

<!-- Interactive Print Controls -->
<div class="actions-wrapper">
    <button class="btn" onclick="window.print()">Print Receipt</button>
    <button class="btn btn-secondary" onclick="window.location.href='/'">Return Home</button>
</div>

</body>
</html>