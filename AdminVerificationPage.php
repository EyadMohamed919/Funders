<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION["UserID"]))
{
    header("Location: /LoginPage.php");
    exit;
}

if(!isset($_SESSION["CanApproveVerification"]) || !$_SESSION["CanApproveVerification"])
{
    header("Location: /ProfilePage.php");
    exit;
}
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
        <a href="/RegisterPage.php">Register</a>
        <a href="/LoginPage.php">Login</a>
        <a href="/ProfilePage.php">Profile</a>
        <a href="/AdminVerificationPage.php">Admin Verification</a>
        <a href="/DonationTypePage.php">Donation Type</a>
        <a href="/PaymentPage.php">Payment</a>
        <a href="/Invoice.php">Invoice</a>

        <form class="logout-form" method="POST" action="/src/routers/UserRouter.php">
            <button class="logout-btn" type="submit" name="logoutUser" value="1">Logout</button>
        </form>
    </nav>

    <div class="container">
        <div class="card">
            <h2>Verification Requests (Admin)</h2>
            <button class="secondary" id="loadRequestsBtn" type="button">Load Requests</button>
            <div id="adminMsg"></div>
            <div id="requestsContainer"></div>
        </div>

        <div class="card">
            <h3>Review Request</h3>
            <form id="reviewForm" method="POST" action="/src/routers/UserRouter.php">
                <div class="form-grid">
                    <div class="field">
                        <label>Verification ID</label>
                        <input type="number" name="verification_id" min="1" required>
                    </div>
                    <div class="field">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="approved">approved</option>
                            <option value="rejected">rejected</option>
                        </select>
                    </div>
                    <div class="field" style="grid-column: span 2;">
                        <label>Note</label>
                        <textarea name="note" rows="3"></textarea>
                    </div>
                </div>
                <button type="submit" name="reviewVerification" value="1">Submit Review</button>
            </form>
            <div id="reviewMsg"></div>
        </div>
    </div>

    <script>
        const loadRequestsBtn = document.getElementById('loadRequestsBtn');
        const adminMsg = document.getElementById('adminMsg');
        const requestsContainer = document.getElementById('requestsContainer');
        const reviewForm = document.getElementById('reviewForm');
        const reviewMsg = document.getElementById('reviewMsg');

        async function loadRequests() {
            const response = await fetch('/src/routers/UserRouter.php?action=all_verification_requests');
            const text = await response.text();

            try {
                const rows = JSON.parse(text);
                adminMsg.className = 'message';
                adminMsg.textContent = 'Requests loaded';

                const bodyRows = (rows || []).map(r => `
                    <tr>
                        <td>${r.verification_request_id}</td>
                        <td>${r.user_id}</td>
                        <td>${r.method}</td>
                        <td>${r.request_status}</td>
                        <td>${r.submitted_at || ''}</td>
                        <td>${r.reviewed_by || ''}</td>
                        <td>${r.reviewed_at || ''}</td>
                    </tr>
                `).join('');

                requestsContainer.innerHTML = `
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
                            </tr>
                        </thead>
                        <tbody>${bodyRows || '<tr><td colspan="7">No requests found</td></tr>'}</tbody>
                    </table>
                `;
            } catch (e) {
                adminMsg.className = 'message error';
                adminMsg.textContent = text || 'Failed to load requests';
                requestsContainer.innerHTML = '';
            }
        }

        loadRequestsBtn.addEventListener('click', loadRequests);

        reviewForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(reviewForm);
            const response = await fetch('/src/routers/UserRouter.php', { method: 'POST', body: formData });
            const text = await response.text();
            reviewMsg.className = text.toLowerCase().includes('failed') || text.toLowerCase().includes('denied') || text.toLowerCase().includes('invalid') ? 'message error' : 'message';
            reviewMsg.textContent = text;
            loadRequests();
        });
    </script>
</body>
</html>