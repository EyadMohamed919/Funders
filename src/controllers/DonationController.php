<?php 
require_once __DIR__ . "/../models/Donation/DonationMoneyStrategy.php";
require_once __DIR__ . "/../models/Donation/DonationServiceStrategy.php";
require_once __DIR__ . "/../models/Donation/DonationGoodsStrategy.php";
require_once __DIR__ . "/../models/Donation/DonationTypes.php";
require_once __DIR__ . "/../models/Donation/DonationModel.php";
require_once __DIR__ . "/../models/Donation/IDonationStrategy.php";
require_once __DIR__ . "/../models/Payment/PaymentModel.php";
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

                case 2:
                    $donation = new DonationServiceStrategy();
                    $donationID = $donationData[3];
                    $name = $donationData[4];
                    $contact = $donationData[5];
                    $data = [$donationID, $name, $contact];
                    break;

                case 3:
                    $donation = new DonationGoodsStrategy();
                    $donationID = $donationData[3];
                    $weight = $donationData[4];
                    $goodsName = $donationData[5];
                    $goodsDetails = $donationData[6];
                    $data = [$donationID, $weight, $goodsName, $goodsDetails];
                    break;
                
                default:
                    break;
            }

            $donation->processDonation($data);
            $donation->updateDonationStatus(2);
            if($donationTypeID == 1)
            {
                header("Location: ../../Invoice.php?donationID=".$donationID);
            }
            else
            {
                header("Location: ../../success.html");
            }
        }
        else
        {
            header("Location: ../../DonationTypePage");
        }
        
        // IDonationStrategy $donation = new DonationModel();
    }

    public static function getTotalAmountOfMoneyRaised($postID)
    {
        $totalAmount = 0;
        $donationModel = new DonationModel();
        $donationsArray = $donationModel->getAllDonationsByPost($postID);
        foreach($donationsArray as $donation)
        {
            $donationID = $donation->getDonationID();
            $donationModel = new DonationMoneyStrategy();
            $donationMoneyStrategy = $donationModel->getDonationByDonationID($donationID);
            if($donationMoneyStrategy)
            {
                $paymentID = $donationMoneyStrategy->getPaymentID();
                $paymentModel = new Payment("", 0.0, []);
                $paymentModel->getPaymentByPaymentID($paymentID);
                $totalAmount += $paymentModel->getAmount();
            }


        }

        return $totalAmount;
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

    public static function getAllDonationByUserID($userID)
    {
        $donationModel = new DonationModel();
        $donationType = new DonationTypes();
        $donationsArray = $donationModel->getAllDonationByUserID($userID);
        // var_dump($donationsArray);
        foreach($donationsArray as $donation)
        {
            $typeName = $donationType->getDonationTypeName($donation->getDonationType());
            $donation->setTypeName($typeName);
        }
        return $donationsArray;
    }

    
}
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// $_SESSION["UserID"] = 99;

