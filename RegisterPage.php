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
$isLoggedIn = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funders | Register</title>
    <link rel="stylesheet" href="/public/css/UserStyles.css">
</head>
<body>
    <nav class="navbar">
        <a href="/index.php">Home</a>
        <a href="/RegisterPage.php">Register</a>
        <a href="/LoginPage.php">Login</a>
        <a href="/DonationTypePage.php">Donation Type</a>
        <a href="/PaymentPage.php">Payment</a>
        <a href="/Invoice.php">Invoice</a>
    </nav>

    <div class="container">
        <div class="card panel">
            <div class="page-header">
                <div>
                    <h1>Create Account</h1>
                    <p class="muted">Simple signup for donors, donees, and admins.</p>
                </div>
                <a class="ghost-link" href="/LoginPage.php">Already have an account?</a>
            </div>

            <form method="POST" action="/src/routers/UserRouter.php">
                <div class="form-grid">
                    <div class="field">
                        <label>Full Name</label>
                        <input type="text" name="full_name" required>
                    </div>
                    <div class="field">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
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
                        <label>Role</label>
                        <select name="role" id="roleSelect" required>
                            <option value="donor">donor</option>
                            <option value="donee">donee</option>
                        </select>
                    </div>
                </div>

                <div id="donorFields" class="subpanel">
                    <h3>Donor Preference</h3>
                    <div class="field">
                        <label>Prefer Anonymous Donations?</label>
                        <select name="is_anonymous">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>

                <div id="doneeFields" class="subpanel" style="display:none;">
                    <h3>Donee Details</h3>
                    <div class="form-grid">
                        <div class="field">
                            <label>National ID</label>
                            <input type="text" name="national_id">
                        </div>
                        <div class="field">
                            <label>Bank Account</label>
                            <input type="text" name="bank_account">
                        </div>
                    </div>
                </div>

                <div class="actions-row">
                    <button type="submit" name="registerUser" value="1">Register</button>
                    <a class="secondary-link" href="/LoginPage.php">Go to Login</a>
                </div>
            </form>

            <?php if ($msg !== ''): ?>
                <div class="message <?php echo $status === 'error' ? 'error' : ''; ?>"><?php echo htmlspecialchars($msg); ?></div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const roleSelect = document.getElementById('roleSelect');
        const donorFields = document.getElementById('donorFields');
        const doneeFields = document.getElementById('doneeFields');

        function updateRoleFields() {
            const role = roleSelect.value;
            donorFields.style.display = role === 'donor' ? 'block' : 'none';
            doneeFields.style.display = role === 'donee' ? 'block' : 'none';
        }

        roleSelect.addEventListener('change', updateRoleFields);
        updateRoleFields();
    </script>
</body>
</html>