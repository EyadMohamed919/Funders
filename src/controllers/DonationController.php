<?php 
require_once __DIR__ . "/../models/DonationTypes.php";
require_once __DIR__ . "/../models/DonationModel.php";
require_once __DIR__ . "/../models/IDonationStrategy.php";
class DonationController{
    public static function getAllDonationTypes()
    {
        $donationTypes = new DonationTypes();
        $types = $donationTypes->getAllDonationTypes();
        return $types;
    }

    public static function createDonation($postID, $donationTypeID)
    {
        switch ($donationTypeID) {
            case 1:
                $donation = new DonationMoneyStrategy();
                break;
            
            default:
                break;
        }
        $data = [$donationTypeID]
        $started = $donation->processDonation($postID);
        if($started)
        {
            header("Location: ../../PaymentPage.php");
        }
        // IDonationStrategy $donation = new DonationModel();
    }
}