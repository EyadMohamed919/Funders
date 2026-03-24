
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Elms+Sans:ital,wght@0,100..900;1,100..900&family=Maven+Pro:wght@400..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/dashboardStyle.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Funders</title>
</head>
<body>
    <nav>
        Funders
    </nav>

    <section>
        <div class="post-container">
        <?php include "../posts/postsFeed.php"; ?>
        </div>

        <div class="post-details">
            <div class="post-details-image"></div>
            <h1 id="title">Title</h1>
            <p id="description">Description</p>
            <p id="category">Medical</p>

            <p id="amount"><strong>3400EGP / 8600EGP</strong></p>
            <div class='bar'>
                <h4 id="target">30% reached <br><i class="fa-solid fa-caret-down"></i></h4>
                <div id="bar" class='bar-overlay'></div>
            </div>
            <a id="link" href="#"><i class="fa-solid fa-hand-holding-dollar"></i>Donate</a>
            
        </div>
    </section>

    <script src="../../../public/scripts/checkDetailsScript.js"></script>
</body>
</html>