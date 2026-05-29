<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID'])) {
    header('Location: /LoginPage.php');
    exit;
}

if (!isset($_SESSION['CanApproveVerification']) || !$_SESSION['CanApproveVerification']) {
    header('Location: /ProfilePage.php?status=error&msg=' . urlencode('Permission denied'));
    exit;
}

require_once __DIR__ . '/src/models/User/UserModel.php';

$userModel = new UserModel();
$requests = $userModel->getAllVerificationRequests();
$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funders | Admin Verification</title>
    <link rel="stylesheet" href="/public/css/UserStyles.css">
</head>
<body>
    <nav class="navbar">
        <a href="/index.php">Home</a>
        <a href="/ProfilePage.php">Profile</a>
        <a href="/AdminVerificationPage.php">Admin Verification</a>
        <a href="/UserManagementPage.php">User Management</a>
        <a href="/AdminDatabaseTablesPage.php">Database Tables</a>
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
                <h1>Verification Review</h1>
                <p class="muted">Simple admin list and review flow.</p>
            </div>
            <div class="actions-row">
                <a class="secondary-link" href="/AdminVerificationPage.php">Refresh Requests</a>
            </div>
        </div>

        <?php if ($msg !== ''): ?>
            <div class="message <?php echo $status === 'error' ? 'error' : ''; ?>"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>

        <div class="card panel">
            <h2>Verification Requests</h2>
            <?php if (count($requests) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Reviewed By</th>
                            <th>Reviewed At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['verification_id']); ?></td>
                                <td><?php echo htmlspecialchars($request['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($request['method']); ?></td>
                                <td><?php echo htmlspecialchars($request['status']); ?></td>
                                <td><?php echo htmlspecialchars($request['submitted_at']); ?></td>
                                <td><?php echo htmlspecialchars($request['reviewed_by'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($request['reviewed_at'] ?? ''); ?></td>
                                <td>
                                    <form method="POST" action="/src/routers/UserRouter.php" class="inline-form">
                                        <input type="hidden" name="verification_id" value="<?php echo (int) $request['verification_id']; ?>">
                                        <select name="status" required>
                                            <option value="approved">approved</option>
                                            <option value="rejected">rejected</option>
                                        </select>
                                        <input type="text" name="note" placeholder="Note">
                                        <button type="submit" name="reviewVerification" value="1">Review</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">No verification requests yet.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>