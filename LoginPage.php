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
        <a href="/RegisterPage.php">Register</a>
        <a href="/LoginPage.php">Login</a>
        <a href="/ProfilePage.php">Profile</a>
        <a href="/AdminVerificationPage.php">Admin Verification</a>
    </nav>

    <div class="container">
        <div class="card">
            <h2>Login</h2>
            <form id="loginForm" method="POST" action="/src/routers/UserRouter.php">
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

                <button type="submit" name="loginUser" value="1">Login</button>
            </form>
            <div id="loginMsg"></div>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const loginMsg = document.getElementById('loginMsg');

        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(loginForm);
            const response = await fetch('/src/routers/UserRouter.php', {
                method: 'POST',
                body: formData
            });

            const text = await response.text();
            loginMsg.className = text.toLowerCase().includes('invalid') || text.toLowerCase().includes('failed') ? 'message error' : 'message';
            loginMsg.textContent = text;
        });
    </script>
</body>
</html>