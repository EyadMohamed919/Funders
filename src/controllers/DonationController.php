<?php 
require_once __DIR__ . "/../models/Donation/DonationMoneyStrategy.php";
require_once __DIR__ . "/../models/Donation/DonationTypes.php";
require_once __DIR__ . "/../models/Donation/DonationModel.php";
require_once __DIR__ . "/../models/Donation/IDonationStrategy.php";
class DonationController{
    public static function getAllDonationTypes()
    {
        $donationTypes = new DonationTypes();
        $types = $donationTypes->getAllDonationTypes();
        return $types;
    }

    public static function createDonation()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if($_SESSION["DonationData"])
        {
            $donationData = $_SESSION["DonationData"];
            // $donationData = [$postID, $donationType, $userID, $donationID];
            $donationTypeID = $donationData[1];
            switch ($donationTypeID) {
                case 1:
                    $donation = new DonationMoneyStrategy();
                    $paymentID = $donationData[4];
                    $donationID = $donationData[3];
                    $data = [$paymentID, $donationID];

                    break;
                
                default:
                    break;
            }

            $donation->processDonation($data);
            header("Location: ../../index.php");
        }
        else
        {
            header("Location: ../../DonationTypePage");
        }
        
        // IDonationStrategy $donation = new DonationModel();
    }

    public static function addPendingDonation($postID, $donationType)
    {
        // This function should be called before going to the Payment/Goods/Service page
        // It initializes a donation ticket with a pending status
        // It then saves the data inside a Session variables to be used after payment or confirmation

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userID = $_SESSION["UserID"];
        $pendingDonation = new DonationModel();
        $donationID = $pendingDonation->addDonation($postID, $donationType, $userID);
        $donationData = [$postID, $donationType, $userID, $donationID];

        if($donationID)
        {
            $_SESSION["DonationData"] = $donationData;
        }
        else
        {
            echo "Failed to add donation";
        }
        
    }
}
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// $_SESSION["UserID"] = 99;

