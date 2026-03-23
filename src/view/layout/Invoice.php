<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("location: /src/view/layout/Login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $invoice->getInvoiceNumber(); ?></title>
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
                <p style="color: var(--primary-color); font-weight: 700;">#<?php echo $invoice->getInvoiceNumber(); ?></p>
            </div>
        </div>

        <div class="details-grid">
            <div class="detail-item">
                <label>Full Name</label>
                <span><?php echo $user->getFname() . " " . $user->getLname(); ?></span>
            </div>
        </div>
        <div class="details-grid">
            <div class="detail-item">
                <label>Issue Date</label>
                <span><?php echo $invoice->getDate(); ?></span>
            </div>
            <div class="detail-item" style="text-align: right;">
                <label>Payment Method</label>
                <span>Credit Card / Digital Wallet</span>
            </div>
        </div>

        <div class="amount-section">
            <div class="total-label">Total Donation Amount</div>
            <div class="total-value">ج.م<?php echo number_format($invoice->getAmount(), 2); ?></div>
        </div>

        <div class="footer-note">
            <button onclick="generatePDF(<?php echo $invoice->getInvoiceNumber(); ?>)" class="download-btn">
                <i class="fa-solid fa-file-pdf"></i> Download as PDF
            </button>
        </div>
    </div>

    <script src="../../../public/scripts/generatePDF.js"></script>
</body>
</html>