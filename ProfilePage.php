<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID'])) {
    header('Location: /LoginPage.php');
    exit;
}

require_once __DIR__ . '/src/models/User/UserModel.php';

$userModel = new UserModel();
$profile = $userModel->getUserWithRolesAndAttributes($_SESSION['UserID']);
$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$roles = $profile['roles'] ?? [];
$attributes = $profile['attributes'] ?? [];
$contacts = $profile['contacts'] ?? [];
$canDonate = isset($_SESSION['CanDonate']) && $_SESSION['CanDonate'];
$canCreatePost = isset($_SESSION['CanCreatePost']) && $_SESSION['CanCreatePost'];
$canApproveVerification = isset($_SESSION['CanApproveVerification']) && $_SESSION['CanApproveVerification'];

$conn = getDatabaseConnection();
$userId = (int) $_SESSION['UserID'];
$transactionResult = $conn->query(" SELECT p.id, p.payment_method, p.amount, a.attribute_name, a.attribute_value FROM payments p JOIN payment_attributes a ON p.id = a.payment_id WHERE p.user_id = $userId ORDER BY p.id DESC");
$transactions = [];
if ($transactionResult) {
    while ($row = $transactionResult->fetch_assoc()) {
        $transactions[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funders | Profile</title>
    <link rel="stylesheet" href="/public/css/UserStyles.css">
</head>
<body>
    <nav class="navbar">
        <a href="/index.php">Home</a>
        <a href="/ProfilePage.php">Profile</a>
        <?php if ($canApproveVerification): ?>
            <a href="/AdminVerificationPage.php">Admin Verification</a>
            <a href="/UserManagementPage.php">User Management</a>
            <a href="/AdminDatabaseTablesPage.php">Database Tables</a>
        <?php endif; ?>
        <a href="/DonationTypePage.php">Donation Type</a>
        <a href="/PaymentPage.php">Payment</a>
        <a href="/Invoice.php">Invoice</a>
        <form class="logout-form" method="POST" action="/src/routers/UserRouter.php">
            <button class="logout-btn" type="submit" name="logoutUser" value="1">Logout</button>
        </form>
    </nav>

    <div class="container">
        <div class="hero-mini card">
            <div>
                <h1>My Dashboard</h1>
                <p class="muted">Your account data, attributes, and verification tools are here.</p>
            </div>
            <div class="actions-row">
                <a class="secondary-link" href="/ProfilePage.php">Refresh Profile</a>
                <a class="secondary-link" href="/index.php">Home</a>
            </div>
        </div>

        <?php if ($msg !== ''): ?>
            <div class="message <?php echo $status === 'error' ? 'error' : ''; ?>"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>

        <div class="split-grid">
            <div class="card panel">
                <h2>Account Summary</h2>
                <div class="kv"><strong>User ID</strong><span><?php echo htmlspecialchars($profile['user_id'] ?? ''); ?></span></div>
                <div class="kv"><strong>Full Name</strong><span><?php echo htmlspecialchars($profile['full_name'] ?? ''); ?></span></div>
                <div class="kv"><strong>Email / Phone</strong><span>
                    <?php if (count($contacts) > 0): ?>
                        <?php echo htmlspecialchars($contacts[0]['contact_value']); ?>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </span></div>
                <div class="kv"><strong>Logged Role</strong><span><?php echo htmlspecialchars($_SESSION['UserRole'] ?? ''); ?></span></div>
                <div class="chips">
                    <span class="chip <?php echo $canDonate ? 'ok' : ''; ?>">Donate: <?php echo $canDonate ? 'Yes' : 'No'; ?></span>
                    <span class="chip <?php echo $canCreatePost ? 'ok' : ''; ?>">Donee Tools: <?php echo $canCreatePost ? 'Yes' : 'No'; ?></span>
                    <span class="chip <?php echo $canApproveVerification ? 'ok' : ''; ?>">Admin Review: <?php echo $canApproveVerification ? 'Yes' : 'No'; ?></span>
                </div>
            </div>

            <div class="card panel">
                <h2>Contacts</h2>
                <?php if (count($contacts) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr><th>Type</th><th>Value</th><th>Primary</th><th>Verified</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contacts as $contact): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($contact['contact_type']); ?></td>
                                    <td><?php echo htmlspecialchars($contact['contact_value']); ?></td>
                                    <td><?php echo !empty($contact['is_primary']) ? 'Yes' : 'No'; ?></td>
                                    <td><?php echo !empty($contact['is_verified']) ? 'Yes' : 'No'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">No contacts found.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="split-grid">
            <div class="card panel">
                <h2>EAV Attributes</h2>
                <?php if (count($attributes) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr><th>Name</th><th>Value</th><th>Type</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attributes as $attribute): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($attribute['attribute_name']); ?></td>
                                    <td><?php echo htmlspecialchars($attribute['value_text']); ?></td>
                                    <td><?php echo htmlspecialchars($attribute['data_type']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">No attributes saved yet.</div>
                <?php endif; ?>
            </div>

            <div class="card panel">
                <h2>Update Attribute</h2>
                <p class="muted">Use the known attribute names below.</p>
                <form method="POST" action="/src/routers/UserRouter.php">
                    <div class="form-grid">
                        <div class="field">
                            <label>Attribute Name</label>
                            <select name="attribute_name" required>
                                <option value="national_id">national_id</option>
                                <option value="bank_account">bank_account</option>
                                <option value="is_anonymous">is_anonymous</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Value</label>
                            <input type="text" name="value_text" required>
                        </div>
                    </div>
                    <button type="submit" name="updateMyAttribute" value="1">Save Attribute</button>
                </form>
            </div>
        </div>

        <div class="split-grid">
            <div class="card panel">
                <h2>Verification Request</h2>
                <p class="muted">Only donee accounts can submit verification requests.</p>
                <form method="POST" action="/src/routers/UserRouter.php">
                    <div class="form-grid">
                        <div class="field">
                            <label>Method</label>
                            <input type="text" name="method" value="document" required>
                        </div>
                        <div class="field">
                            <label>Note</label>
                            <input type="text" name="note">
                        </div>
                    </div>
                    <button type="submit" name="requestVerification" value="1">Submit Verification Request</button>
                </form>
            </div>

            <div class="card panel">
                <h2>Your Role Tools</h2>
                <div class="stacked-links">
                    <?php if ($canDonate): ?>
                        <a class="secondary-link" href="/DonationTypePage.php">Go to Donation Flow</a>
                    <?php endif; ?>
                    <?php if ($canApproveVerification): ?>
                        <a class="secondary-link" href="/AdminVerificationPage.php">Open Admin Verification</a>
                        <a class="secondary-link" href="/UserManagementPage.php">Open User Management</a>
                        <a class="secondary-link" href="/AdminDatabaseTablesPage.php">Open Database Tables</a>
                    <?php endif; ?>
                    <?php if ($canCreatePost): ?>
                        <div class="empty-state">Donee tools are available for your role.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="split-grid">
            <div class="card panel" style="grid-column: span 2;">
                <h2>My Transactions</h2>
                <?php if (count($transactions) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Attribute</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['payment_method']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['amount']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['attribute_name']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['attribute_value']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">No transactions found.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="split-grid">
            <div class="card panel" style="grid-column: span 2;">
                <h2>Donated Type</h2>
                <?php 
                require_once __DIR__ . "/src/views/DonationViews.php";
                DonationViews::fetchMyDonatedTypes($_SESSION["UserID"]);
                ?>
            </div>
        </div>

    </div>
</body>
</html>