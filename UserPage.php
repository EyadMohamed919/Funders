<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funders | User Test Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 30px auto;
            padding: 0 12px;
        }

        h1 {
            margin-bottom: 8px;
        }

        .hint {
            color: #444;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #d0d0d0;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 16px;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 8px;
        }

        input, select, button {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        button {
            cursor: pointer;
        }

        .single {
            margin-bottom: 8px;
        }

        .profile-link {
            display: inline-block;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <h1>User Test Page</h1>
    <p class="hint">Use this page to test register, login, EAV update, and verification request.</p>

    <div class="card">
        <h3>Register</h3>
        <form method="POST" action="/src/routers/UserRouter.php">
            <div class="row">
                <input type="text" name="full_name" placeholder="Full name" required>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="row">
                <select name="contact_type" required>
                    <option value="email">email</option>
                    <option value="phone">phone</option>
                </select>
                <input type="text" name="contact_value" placeholder="Email or phone" required>
            </div>

            <div class="row">
                <select name="role" required>
                    <option value="donor">donor</option>
                    <option value="donee">donee</option>
                    <option value="admin">admin</option>
                </select>
                <input type="text" name="is_anonymous" placeholder="Donor only: 0 or 1">
            </div>

            <div class="row">
                <input type="text" name="is_laundering_flag" placeholder="Donor only: 0 or 1">
                <input type="text" name="national_id" placeholder="Donee only: national_id">
            </div>

            <div class="single">
                <input type="text" name="bank_account" placeholder="Donee only: bank_account">
            </div>

            <button name="registerUser" value="1" type="submit">Register User</button>
        </form>
    </div>

    <div class="card">
        <h3>Login</h3>
        <form method="POST" action="/src/routers/UserRouter.php">
            <div class="row">
                <select name="contact_type" required>
                    <option value="email">email</option>
                    <option value="phone">phone</option>
                </select>
                <input type="text" name="contact_value" placeholder="Email or phone" required>
            </div>

            <div class="single">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button name="loginUser" value="1" type="submit">Login</button>
        </form>
    </div>

    <div class="card">
        <h3>Update One Attribute</h3>
        <form method="POST" action="/src/routers/UserRouter.php">
            <div class="row">
                <input type="text" name="attribute_name" placeholder="attribute_name (example: national_id)" required>
                <input type="text" name="value_text" placeholder="value_text" required>
            </div>

            <button name="updateMyAttribute" value="1" type="submit">Save Attribute</button>
        </form>
    </div>

    <div class="card">
        <h3>Request Verification</h3>
        <form method="POST" action="/src/routers/UserRouter.php">
            <div class="row">
                <input type="text" name="method" placeholder="method" value="document">
                <input type="text" name="note" placeholder="note">
            </div>

            <button name="requestVerification" value="1" type="submit">Request Verification</button>
        </form>
    </div>

    <div class="card">
        <h3>My Profile</h3>
        <a class="profile-link" href="/src/routers/UserRouter.php?action=my_profile" target="_blank">Open my profile JSON</a>
    </div>
</body>
</html>