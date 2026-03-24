<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: /src/view/layout/Login.php");
    exit();
}
?>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/subscriptionStyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Elms+Sans:ital,wght@0,100..900;1,100..900&family=Maven+Pro:wght@400..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Subscription</title>
</head>
<body>
     
    <form action="../../router/SubscriptionRouter.php" method="post">
        <h1>Subscription</h1>
        <p>Subscribe to a recurring amount to be payed randomly</p>
        <input type="number" id="amount" name="amount" placeholder="500">
    </input>
    <h2>Quick amounts: </h2>
        <ul>
            <li onclick="changeAmount(100)">100EGP</li>
            <li onclick="changeAmount(250)">250EGP</li>
            <li onclick="changeAmount(500)">500EGP</li>
            <li onclick="changeAmount(1000)">1000EGP</li>
        </ul>
        <h2>Frequency: </h2>

        <div class="frequency">
            I want to pay this amount every 
            <select name="frequency" id="">
               <option value="monthly">Monthly</option>
               <option value="weekly">Weekly</option> 
            </select>
        </div>


        <button type="submit">Subscription</button>
        <a  href="Dashboard.php">Return to Dashboard</a>
    </form>

    <script src="../../../public/scripts/subscriptionScript.js"></script>
   
</body>
</html>