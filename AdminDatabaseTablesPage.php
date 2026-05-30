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

require_once __DIR__ . '/config/db.php';

$conn = getDatabaseConnection();

function fetchTableRows($conn, $tableName)
{
    $rows = [];
    $result = $conn->query("SELECT * FROM {$tableName}");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}

$tableOptions = [
    'users',
    'user_contacts',
    'roles',
    'user_roles',
    'user_attribute_definitions',
    'user_attribute_values',
    'user_verification_requests',
    'payments',
    'payment_attributes'
];

$selectedTable = isset($_GET['table']) ? $_GET['table'] : 'users';
if (!in_array($selectedTable, $tableOptions, true)) {
    $selectedTable = 'users';
}

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$rows = fetchTableRows($conn, $selectedTable);
$filteredRows = [];
$searchLower = strtolower($search);

foreach ($rows as $row) {
    if ($searchLower === '') {
        $filteredRows[] = $row;
        continue;
    }

    foreach ($row as $value) {
        if (strpos(strtolower((string) $value), $searchLower) !== false) {
            $filteredRows[] = $row;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funders | Admin Database Tables</title>
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
                <h1>Database Tables</h1>
                <p class="muted">Simple read-only view of user and payment database tables.</p>
            </div>
            <div class="actions-row">
                <a class="secondary-link" href="/AdminDatabaseTablesPage.php">Refresh</a>
            </div>
        </div>

        <div class="card panel">
            <h2>Table Viewer</h2>
            <form method="GET" action="/AdminDatabaseTablesPage.php" class="form-grid">
                <div class="field">
                    <label>Choose Table</label>
                    <select name="table">
                        <?php foreach ($tableOptions as $tableName): ?>
                            <option value="<?php echo htmlspecialchars($tableName); ?>" <?php echo $selectedTable === $tableName ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tableName); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label>Search In Table</label>
                    <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search any value">
                </div>
                <div class="actions-row">
                    <button type="submit">Load Table</button>
                    <a class="secondary-link" href="/AdminDatabaseTablesPage.php">Clear</a>
                </div>
            </form>
        </div>

        <div class="card panel">
            <h2><?php echo htmlspecialchars($selectedTable); ?> (<?php echo count($filteredRows); ?>)</h2>
            <?php if (count($filteredRows) > 0): ?>
                <?php $headers = array_keys($filteredRows[0]); ?>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <?php foreach ($headers as $header): ?>
                                    <th><?php echo htmlspecialchars($header); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filteredRows as $row): ?>
                                <tr>
                                    <?php foreach ($headers as $header): ?>
                                        <td><?php echo htmlspecialchars((string) ($row[$header] ?? '')); ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">No rows found for this table and filter.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>