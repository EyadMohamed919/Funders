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
        <a href="/ProfilePage.php">Profile</a>
        <a href="/AdminVerificationPage.php">Admin Verification</a>
    </nav>

    <div class="container">
        <div class="card">
            <h2>Create Account</h2>
            <form id="registerForm" method="POST" action="/src/routers/UserRouter.php">
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
                        <select name="contact_type" id="contactType" required>
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
                            <option value="admin">admin</option>
                        </select>
                    </div>
                </div>

                <div id="donorFields" class="card" style="margin-top: 10px;">
                    <h3>Donor Fields</h3>
                    <div class="form-grid">
                        <div class="field">
                            <label>is_anonymous (0 or 1)</label>
                            <input type="number" name="is_anonymous" min="0" max="1" value="0">
                        </div>
                        <div class="field">
                            <label>is_laundering_flag (0 or 1)</label>
                            <input type="number" name="is_laundering_flag" min="0" max="1" value="0">
                        </div>
                    </div>
                </div>

                <div id="doneeFields" class="card" style="display:none; margin-top: 10px;">
                    <h3>Donee Fields</h3>
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

                <button type="submit" name="registerUser" value="1">Register</button>
            </form>
            <div id="registerMsg"></div>
        </div>
    </div>

    <script>
        const roleSelect = document.getElementById('roleSelect');
        const donorFields = document.getElementById('donorFields');
        const doneeFields = document.getElementById('doneeFields');
        const registerForm = document.getElementById('registerForm');
        const registerMsg = document.getElementById('registerMsg');

        function updateRoleFields() {
            const role = roleSelect.value;
            donorFields.style.display = role === 'donor' ? 'block' : 'none';
            doneeFields.style.display = role === 'donee' ? 'block' : 'none';
        }

        roleSelect.addEventListener('change', updateRoleFields);
        updateRoleFields();

        registerForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(registerForm);
            const response = await fetch('/src/routers/UserRouter.php', {
                method: 'POST',
                body: formData
            });

            const text = await response.text();
            registerMsg.className = text.toLowerCase().includes('failed') || text.toLowerCase().includes('invalid') ? 'message error' : 'message';
            registerMsg.textContent = text;
        });
    </script>
</body>
</html>