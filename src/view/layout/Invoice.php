<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: /src/view/layout/Login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $invoiceNumber; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/invoiceStyle.css">
</head>
<body>

    <div class="invoice-card">
        <div class="invoice-header">
            <div class="brand-info">
                <div class="brand-name">FUNDERS</div>
                <p style="font-size: 12px; color: var(--text-light);">Empowering Change Everywhere</p>
            </div>
            <div class="invoice-meta">
                <h1>INVOICE</h1>
                <p style="color: var(--primary-color); font-weight: 700;">#<?php echo $invoiceNumber; ?></p>
            </div>
        </div>

        <div class="details-grid">
            <div class="detail-item">
                <label>Issue Date</label>
                <span><?php echo $date; ?></span>
            </div>
            <div class="detail-item" style="text-align: right;">
                <label>Payment Method</label>
                <span>Credit Card / Digital Wallet</span>
            </div>
        </div>

        <div class="amount-section">
            <div class="total-label">Total Donation Amount</div>
            <div class="total-value">$<?php echo number_format($amount, 2); ?></div>
        </div>

        <div class="footer-note">
            <p>Thank you for your generous contribution. This receipt confirms your donation to the selected cause.</p>
            <p><strong>Funders Platform &copy; 2026</strong></p>
        </div>
    </div>

</body>
</html>