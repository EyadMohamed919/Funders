<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION["UserID"]))
{
    header("Location: /LoginPage.php");
    exit;
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
            <h2>My Profile</h2>
            <button class="secondary" id="refreshProfileBtn" type="button">Refresh Profile</button>
            <div id="profileMsg"></div>
            <div id="profileContent" style="margin-top: 12px;"></div>
        </div>

        <div class="card">
            <h3>Update My Attribute (EAV)</h3>
            <form id="attrForm" method="POST" action="/src/routers/UserRouter.php">
                <div class="form-grid">
                    <div class="field">
                        <label>Attribute Name</label>
                        <input type="text" name="attribute_name" placeholder="example: national_id" required>
                    </div>
                    <div class="field">
                        <label>Value</label>
                        <input type="text" name="value_text" required>
                    </div>
                </div>
                <button type="submit" name="updateMyAttribute" value="1">Save Attribute</button>
            </form>
            <div id="attrMsg"></div>
        </div>

        <div class="card">
            <h3>Request Verification</h3>
            <form id="verificationForm" method="POST" action="/src/routers/UserRouter.php">
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
            <div id="verifyMsg"></div>
        </div>
    </div>

    <script>
        const profileMsg = document.getElementById('profileMsg');
        const profileContent = document.getElementById('profileContent');
        const refreshProfileBtn = document.getElementById('refreshProfileBtn');
        const attrForm = document.getElementById('attrForm');
        const attrMsg = document.getElementById('attrMsg');
        const verificationForm = document.getElementById('verificationForm');
        const verifyMsg = document.getElementById('verifyMsg');

        async function loadProfile() {
            const response = await fetch('/src/routers/UserRouter.php?action=my_profile');
            const text = await response.text();

            try {
                const data = JSON.parse(text);
                profileMsg.className = 'message';
                profileMsg.textContent = 'Profile loaded';

                const contactsRows = (data.contacts || []).map(c => `<tr><td>${c.contact_type}</td><td>${c.contact_value}</td><td>${c.is_primary}</td><td>${c.is_verified}</td></tr>`).join('');
                const rolesRows = (data.roles || []).map(r => `<tr><td>${r.role_id}</td><td>${r.role_name}</td></tr>`).join('');
                const attrRows = (data.attributes || []).map(a => `<tr><td>${a.attribute_name}</td><td>${a.value_text}</td><td>${a.data_type}</td></tr>`).join('');

                profileContent.innerHTML = `
                    <div class="kv"><strong>User ID</strong><span>${data.user_id}</span></div>
                    <div class="kv"><strong>Full Name</strong><span>${data.full_name}</span></div>
                    <div class="kv"><strong>Created At</strong><span>${data.created_at}</span></div>

                    <h3>Contacts</h3>
                    <table class="table">
                        <thead><tr><th>Type</th><th>Value</th><th>Primary</th><th>Verified</th></tr></thead>
                        <tbody>${contactsRows || '<tr><td colspan="4">No contacts</td></tr>'}</tbody>
                    </table>

                    <h3>Roles</h3>
                    <table class="table">
                        <thead><tr><th>ID</th><th>Role</th></tr></thead>
                        <tbody>${rolesRows || '<tr><td colspan="2">No roles</td></tr>'}</tbody>
                    </table>

                    <h3>Attributes</h3>
                    <table class="table">
                        <thead><tr><th>Name</th><th>Value</th><th>Type</th></tr></thead>
                        <tbody>${attrRows || '<tr><td colspan="3">No attributes</td></tr>'}</tbody>
                    </table>
                `;
            } catch (e) {
                profileMsg.className = 'message error';
                profileMsg.textContent = text || 'Failed to load profile';
                profileContent.innerHTML = '';
            }
        }

        refreshProfileBtn.addEventListener('click', loadProfile);

        attrForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(attrForm);
            const response = await fetch('/src/routers/UserRouter.php', { method: 'POST', body: formData });
            const text = await response.text();
            attrMsg.className = text.toLowerCase().includes('failed') ? 'message error' : 'message';
            attrMsg.textContent = text;
            loadProfile();
        });

        verificationForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(verificationForm);
            const response = await fetch('/src/routers/UserRouter.php', { method: 'POST', body: formData });
            const text = await response.text();
            verifyMsg.className = text.toLowerCase().includes('failed') || text.toLowerCase().includes('denied') ? 'message error' : 'message';
            verifyMsg.textContent = text;
        });

        loadProfile();
    </script>
</body>
</html>