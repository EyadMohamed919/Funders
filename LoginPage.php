<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['UserID'])) {
    header('Location: /ProfilePage.php');
    exit;
}

$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funders | Login</title>
    <link rel="stylesheet" href="/public/css/UserStyles.css">
</head>
<body>
    <nav class="navbar">
        <a href="/index.php">Home</a>
        <a href="/LoginPage.php">Login</a>
        <a href="/RegisterPage.php">Register</a>
        <a href="/DonationTypePage.php">Donation Type</a>
        <a href="/PaymentPage.php">Payment</a>
        <a href="/Invoice.php">Invoice</a>
    </nav>

    <div class="container">
        <div class="card panel">
            <div class="page-header">
                <div>
                    <h1>Login</h1>
                    <p class="muted">Use email or phone with your password.</p>
                </div>
                <a class="ghost-link" href="/RegisterPage.php">Need an account?</a>
            </div>

            <form method="POST" action="/src/routers/UserRouter.php">
                <div class="form-grid">
                    <div class="field">
                        <label>Contact Type</label>
                        <select name="contact_type" required>
                            <option value="email">email</option>
                            <option value="phone">phone</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Contact Value</label>
                        <input type="text" name="contact_value" required>
                    </div>
                    <div class="field">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                </div>

                <div class="actions-row">
                    <button type="submit" name="loginUser" value="1">Login</button>
                    <a class="secondary-link" href="/RegisterPage.php">Register</a>
                </div>
            </form>

            <?php if ($msg !== ''): ?>
                <div class="message <?php echo $status === 'error' ? 'error' : ''; ?>"><?php echo htmlspecialchars($msg); ?></div>
            <?php endif; ?>

            <?php if ($status === 'error'): ?>
                <a class="register-link" href="/RegisterPage.php">No account? Register here</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>