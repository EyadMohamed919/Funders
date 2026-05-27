<?php
require_once __DIR__ . "/../controllers/DonationController.php";
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(isset($_POST["donationTypePage"]))
    {
        $donationTypeID = $_POST["donationType"];
        $postID = $_POST["postID"];
        switch ($donationTypeID) {
            case 1:
                DonationController::addPendingDonation($postID, $donationTypeID);
                header("Location: ../../PaymentPage.php");
                break;
            
            default:
                break;
        }
    }
}
else if($_SERVER["REQUEST_METHOD"] == "GET")
{
    
}