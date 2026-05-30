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

            case 2:
                DonationController::addPendingDonation($postID, $donationTypeID);
                header("Location: ../../ServiceDonationPage.php");
                break;

            case 3:
                DonationController::addPendingDonation($postID, $donationTypeID);
                header("Location: ../../GoodsDonationPage.php");
                break;
            
            default:
                break;
        }
    }
    else if(isset($_POST["goodsDonation"]))
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $donationData = $_SESSION["DonationData"];
        array_push($donationData, $_POST["weight"], $_POST["goodsName"], $_POST["goodsDetails"]);
        $_SESSION["DonationData"] = $donationData;
        DonationController::createDonation();
    }
    else if(isset($_POST["serviceDonation"]))
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $donationData = $_SESSION["DonationData"];
        array_push($donationData, $_POST["name"], $_POST["contact"]);
        $_SESSION["DonationData"] = $donationData;
        DonationController::createDonation();
    }
}
else if($_SERVER["REQUEST_METHOD"] == "GET")
{
    
}