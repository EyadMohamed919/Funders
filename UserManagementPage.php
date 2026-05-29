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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['manage_action']) ? $_POST['manage_action'] : '';
    $userID = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;

    if ($action === 'update_user') {
        $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
        $roleID = isset($_POST['role_id']) ? (int) $_POST['role_id'] : 0;

        if ($userID <= 0 || $fullName === '' || $roleID <= 0) {
            header('Location: /UserManagementPage.php?status=error&msg=' . urlencode('Invalid update data'));
            exit;
        }

        $conn->begin_transaction();
        try {
            $updateUser = $conn->prepare('UPDATE users SET full_name = ? WHERE user_id = ?');
            $updateUser->bind_param('si', $fullName, $userID);
            $updateUser->execute();

            $deleteRoles = $conn->prepare('DELETE FROM user_roles WHERE user_id = ?');
            $deleteRoles->bind_param('i', $userID);
            $deleteRoles->execute();

            $insertRole = $conn->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)');
            $insertRole->bind_param('ii', $userID, $roleID);
            $insertRole->execute();

            $conn->commit();
            header('Location: /UserManagementPage.php?status=success&msg=' . urlencode('User updated'));
            exit;
        } catch (Throwable $e) {
            $conn->rollback();
            header('Location: /UserManagementPage.php?status=error&msg=' . urlencode('Update failed'));
            exit;
        }
    }

    if ($action === 'delete_user') {
        if ($userID <= 0) {
            header('Location: /UserManagementPage.php?status=error&msg=' . urlencode('Invalid user id'));
            exit;
        }

        if ($userID === (int) $_SESSION['UserID']) {
            header('Location: /UserManagementPage.php?status=error&msg=' . urlencode('You cannot delete your own account'));
            exit;
        }

        $conn->begin_transaction();
        try {
            $clearReviewedBy = $conn->prepare('UPDATE user_verification_requests SET reviewed_by = NULL WHERE reviewed_by = ?');
            $clearReviewedBy->bind_param('i', $userID);
            $clearReviewedBy->execute();

            $deleteVerification = $conn->prepare('DELETE FROM user_verification_requests WHERE user_id = ?');
            $deleteVerification->bind_param('i', $userID);
            $deleteVerification->execute();

            $deleteAttributes = $conn->prepare('DELETE FROM user_attribute_values WHERE user_id = ?');
            $deleteAttributes->bind_param('i', $userID);
            $deleteAttributes->execute();

            $deleteContacts = $conn->prepare('DELETE FROM user_contacts WHERE user_id = ?');
            $deleteContacts->bind_param('i', $userID);
            $deleteContacts->execute();

            $deleteRoles = $conn->prepare('DELETE FROM user_roles WHERE user_id = ?');
            $deleteRoles->bind_param('i', $userID);
            $deleteRoles->execute();

            $deleteUser = $conn->prepare('DELETE FROM users WHERE user_id = ?');
            $deleteUser->bind_param('i', $userID);
            $deleteUser->execute();

            $conn->commit();
            header('Location: /UserManagementPage.php?status=success&msg=' . urlencode('User deleted'));
            exit;
        } catch (Throwable $e) {
            $conn->rollback();
            header('Location: /UserManagementPage.php?status=error&msg=' . urlencode('Delete failed. This user may be referenced in other records.'));
            exit;
        }
    }
}

$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$roleFilterID = isset($_GET['role_id']) ? (int) $_GET['role_id'] : 0;

$users = [];
$roles = [];
$userRoles = [];
$contacts = [];

$usersResult = $conn->query('SELECT user_id, full_name, created_at FROM users ORDER BY user_id ASC');
if ($usersResult) {
    while ($row = $usersResult->fetch_assoc()) {
        $users[] = $row;
    }
}

$rolesResult = $conn->query('SELECT role_id, role_name FROM roles ORDER BY role_id ASC');
if ($rolesResult) {
    while ($row = $rolesResult->fetch_assoc()) {
        $roles[] = $row;
    }
}

$userRolesResult = $conn->query('SELECT user_id, role_id FROM user_roles');
if ($userRolesResult) {
    while ($row = $userRolesResult->fetch_assoc()) {
        $userRoles[] = $row;
    }
}

$contactsResult = $conn->query('SELECT user_id, contact_value, is_primary FROM user_contacts');
if ($contactsResult) {
    while ($row = $contactsResult->fetch_assoc()) {
        $contacts[] = $row;
    }
}

$roleNameByID = [];
foreach ($roles as $role) {
    $roleNameByID[(int) $role['role_id']] = $role['role_name'];
}

$roleIDsByUser = [];
foreach ($userRoles as $row) {
    $uid = (int) $row['user_id'];
    $rid = (int) $row['role_id'];
    if (!isset($roleIDsByUser[$uid])) {
        $roleIDsByUser[$uid] = [];
    }
    $roleIDsByUser[$uid][] = $rid;
}

$primaryContactByUser = [];
foreach ($contacts as $row) {
    $uid = (int) $row['user_id'];
    if (!isset($primaryContactByUser[$uid]) || (int) $row['is_primary'] === 1) {
        $primaryContactByUser[$uid] = $row['contact_value'];
    }
}

$filteredUsers = [];
$qLower = strtolower($q);
foreach ($users as $user) {
    $uid = (int) $user['user_id'];
    $name = $user['full_name'];
    $contactValue = isset($primaryContactByUser[$uid]) ? $primaryContactByUser[$uid] : '';
    $userRoleIDs = isset($roleIDsByUser[$uid]) ? $roleIDsByUser[$uid] : [];

    $matchesRole = $roleFilterID === 0 || in_array($roleFilterID, $userRoleIDs, true);
    $matchesText = true;
    if ($qLower !== '') {
        $matchesText = strpos(strtolower((string) $uid), $qLower) !== false
            || strpos(strtolower($name), $qLower) !== false
            || strpos(strtolower($contactValue), $qLower) !== false;
    }

    if ($matchesRole && $matchesText) {
        $filteredUsers[] = $user;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funders | User Management</title>
    <link rel="stylesheet" href="/public/css/UserStyles.css">
</head>
<body>
    <nav class="navbar">
        <a href="/index.php">Home</a>
        <a href="/ProfilePage.php">Profile</a>
        <a href="/AdminVerificationPage.php">Admin Verification</a>
        <a href="/UserManagementPage.php">User Management</a>
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
                <h1>User Management</h1>
                <p class="muted">Filter users, update name or role, and delete users.</p>
            </div>
            <div class="actions-row">
                <a class="secondary-link" href="/UserManagementPage.php">Refresh</a>
            </div>
        </div>

        <?php if ($msg !== ''): ?>
            <div class="message <?php echo $status === 'error' ? 'error' : ''; ?>"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>

        <div class="card panel">
            <h2>Filters</h2>
            <form method="GET" action="/UserManagementPage.php" class="form-grid">
                <div class="field">
                    <label>Search by ID, name, or contact</label>
                    <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="e.g. 3 or ahmed">
                </div>
                <div class="field">
                    <label>Role</label>
                    <select name="role_id">
                        <option value="0">All roles</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo (int) $role['role_id']; ?>" <?php echo $roleFilterID === (int) $role['role_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($role['role_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="actions-row">
                    <button type="submit">Apply Filter</button>
                    <a class="secondary-link" href="/UserManagementPage.php">Clear</a>
                </div>
            </form>
        </div>

        <div class="card panel">
            <h2>Users (<?php echo count($filteredUsers); ?>)</h2>
            <?php if (count($filteredUsers) > 0): ?>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Primary Contact</th>
                                <th>Role</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filteredUsers as $user): ?>
                                <?php
                                    $uid = (int) $user['user_id'];
                                    $assignedRoleIDs = isset($roleIDsByUser[$uid]) ? $roleIDsByUser[$uid] : [];
                                    $selectedRoleID = count($assignedRoleIDs) > 0 ? (int) $assignedRoleIDs[0] : 0;
                                    $roleName = $selectedRoleID > 0 && isset($roleNameByID[$selectedRoleID]) ? $roleNameByID[$selectedRoleID] : 'none';
                                ?>
                                <tr>
                                    <td><?php echo $uid; ?></td>
                                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($primaryContactByUser[$uid] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($roleName); ?></td>
                                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                                    <td>
                                        <form method="POST" action="/UserManagementPage.php" class="inline-form">
                                            <input type="hidden" name="manage_action" value="update_user">
                                            <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
                                            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                            <select name="role_id" required>
                                                <?php foreach ($roles as $role): ?>
                                                    <option value="<?php echo (int) $role['role_id']; ?>" <?php echo $selectedRoleID === (int) $role['role_id'] ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($role['role_name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit">Update</button>
                                        </form>

                                        <form method="POST" action="/UserManagementPage.php" onsubmit="return confirm('Delete this user and all related user records?');">
                                            <input type="hidden" name="manage_action" value="delete_user">
                                            <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
                                            <button type="submit" class="danger-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">No users found for this filter.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>