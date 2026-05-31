<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION["UserID"]);
$isAdmin = isset($_SESSION["CanApproveVerification"]) && $_SESSION["CanApproveVerification"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funders | Empowering Change</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="public/css/index.css">
</head>
<body>

    <nav class="main-nav">
        <div class="nav-container">
            <a href="index.php" class="brand-logo">FUNDERS</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="DonationTypePage.php">Donate</a></li>
                <li><a href="PaymentPage.php">Payment</a></li>
                <li><a href="Invoice.php">Invoice</a></li>
                <?php if(!$isLoggedIn): ?>
                    <li><a href="RegisterPage.php">Register</a></li>
                    <li><a href="LoginPage.php" class="login-btn">Login</a></li>
                <?php else: ?>
                    <li><a href="ProfilePage.php">Profile</a></li>
                    <?php if($isAdmin): ?>
                        <li><a href="AdminVerificationPage.php">Admin</a></li>
                        <li><a href="UserManagementPage.php">User Management</a></li>
                        <li><a href="AdminDatabaseTablesPage.php">Database Tables</a></li>
                    <?php endif; ?>
                    <li>
                        <form class="logout-inline" method="POST" action="/src/routers/UserRouter.php">
                            <button type="submit" name="logoutUser" value="1" class="logout-inline-btn">Logout</button>
                        </form>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <header class="hero-section">
        <div class="hero-content">
            <h1>Support a Cause, <span class="text-gradient">Change a Life.</span></h1>
            <p>The transparent platform for medical, educational, and community fundraising.</p>
            <?php if(!$isLoggedIn): ?>
                <a href="RegisterPage.php" class="cta-button">Start Exploring</a>
            <?php else: ?>
                <a href="ProfilePage.php" class="cta-button">Go To My Profile</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="content-wrapper" id="explore">
        <section class="section-header">
            <h2>Active Fundraisers</h2>
            <p>View the latest verified requests from our community.</p>
        </section>

        <div class="donation-container">
            <?php 
            require_once __DIR__ . "/src/views/PostView.php";
            PostView::fetchAllPosts();
            ?>
        </div>
    </main>

    <footer class="main-footer">
        <p>&copy; 2026 Funders Platform. All rights reserved.</p>
    </footer>

</body>
</html>