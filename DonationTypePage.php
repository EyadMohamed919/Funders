<?php 
require_once __DIR__ . "/src/views/DonationViews.php";
require_once __DIR__ . "/src/controllers/PostController.php";
if(isset($_GET["postID"]))
{
    $postID = $_GET["postID"];
}
else
{
    $postID = 9999; // Da placeholder bas
}
$postController = new PostController();
$post = $postController->show($postID);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/DonationTypeStyles.css">
    <title>Funders | Donation Type</title>
</head>
<body>

<div class="card-container">
    
    <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80" alt="Reforestation project" class="post-image">
    
    <div class="post-content">
        
        <h1 class="post-title"><?php echo $post->getTitle(); ?></h1>
        <p class="post-description">
            <?php echo $post->getDetails(); ?>
        </p>
    </div>

    <form method="post" action="/src/routers/DonationRouter.php" class="donation-section">
        <label for="donation_type" class="donation-label">Select Donation Type</label>
            <?php DonationViews::fetchAllDonationTypes(); ?>
            <input type="number" name="postID" style="display: none;" value="<?php echo $postID; ?>">
        <p class="help-text">Selecting an option will safely redirect you to our dedicated portal.</p>
        <button name="donationTypePage">Continue -></button>
    </form>

</div>

<!-- <script>
    const targetPages = {
        '1': 'src/routers/DonationRouter.php?donationTypePage=true&donationType=1&postID=',
        '2': 'src/routers/DonationRouter.php?donationTypePage=true&donationType=2&postID=',
        '3': 'src/routers/DonationRouter.php?donationTypePage=true&donationType=3&postID=',
    };

    const selectElement = document.getElementById('donation_type');

    selectElement.addEventListener('change', function () {
        const selectedValue = this.value;
        const targetUrl = targetPages[selectedValue];

        if (targetUrl) {
            // Option 1: Redirect the current page
            window.location.href = targetUrl;

            // Option 2: If you want to open it in a new browser tab instead, 
            // comment out the line above and uncomment the line below:
            // window.open(targetUrl, '_blank');
        }
    });
</script> -->

</body>
</html>