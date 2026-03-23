<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../public/css/loginStyle.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<main class="login-card">
        <header class="login-header">
            <h2>Funders</h2>
            <h3>Login</h3>
        </header>

        <form class="login-form" method="post" action="../../router/UserRouter.php">
        <?php     
        session_start();
        if(isset($_SESSION["LOGIN_ERROR"]))
        {
            echo '<p class="error">' . $_SESSION["LOGIN_ERROR"] . '</p>';
        }
     
        ?>
            <div class="form-group">
                <label class="input-label" for="email">Email Address</label>
                <input type="email" name="email" class="input-field" placeholder="example@example.com" required>
            </div>

            <div class="form-group">
                <label class="input-label" for="password">Password</label>
                <input type="password" name="password" class="input-field" placeholder="••••••••" required>
            </div>

            <input type="text" name="router" value="login" hidden>
            
            <button type="submit" class="submit-button">Sign In</button>
        </form>

        <footer class="footer-links">
            <a href="#">Forgot password?</a>
        </footer>
    </main>
</body>
</html>