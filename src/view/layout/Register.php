<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../public/css/loginStyle.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
<main class="login-card">
        <header class="login-header">
            <h2>Funders</h2>
            <h3>Register</h3>
        </header>

        <form class="login-form" method="post" action="../../router/UserRouter.php">
        <?php     
        session_start();
        if(isset($_SESSION["REGISTER_ERROR"]))
        {
            echo '<p class="error">' . $_SESSION["REGISTER_ERROR"] . '</p>';
        }
     
        ?>

            <div class="form-group">
                <label class="input-label" for="email">First Name</label>
                <input type="fname" name="fname" class="input-field" placeholder="First Name" required>
            </div>

            <div class="form-group">
                <label class="input-label" for="email">Last Name</label>
                <input type="lname" name="lname" class="input-field" placeholder="Last Name" required>
            </div>

            <div class="form-group">
                <label class="input-label" for="email">Email Address</label>
                <input type="email" name="email" class="input-field" placeholder="example@example.com" required>
            </div>

            <div class="form-group">
                <label class="input-label" for="email">Phone</label>
                <input type="tel" name="phone" class="input-field" placeholder="0113598671" required>
            </div>

            <div class="form-group">
                <label class="input-label" for="password">Password</label>
                <input type="password" name="password" class="input-field" placeholder="••••••••" required>
            </div>

            <input type="text" name="router" value="register" hidden>
            
            <button type="submit" class="submit-button">Sign Up</button>
        </form>

    </main>
</body>
</html>