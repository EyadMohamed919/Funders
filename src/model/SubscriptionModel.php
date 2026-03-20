<?php

require_once("../model/UserModel.php");
require_once("../../config/db.php");
class Subscription{

    private $subscriptionID;
    private $frequency;
    private $nextBillingDate;

    public function createSubscription($frequency, $userID)
    {
        $frequency = htmlspecialchars(trim($frequency));

        if($frequency == "monthly")
        {
            $date = new DateTime();
    
            $date->modify('+30 days');
    
            $db_date = $date->format('Y-m-d');
    
            echo "The date 30 days from now is: " . $db_date;
        }
        else if($frequency == "weekly")
        {
            $date = new DateTime();
    
            $date->modify('+7 days');
    
            $db_date = $date->format('Y-m-d');
    
        }
        else
        {
            $date = new DateTime();
    
            $date->modify('+30 days');
    
            $db_date = $date->format('Y-m-d');
    
            echo "The date 30 days from now is: " . $db_date;
        }

        $currentDate = new DateTime();
        $currentDate = $currentDate->format('Y-m-d');
        $status = "active";

        $stmt = getDatabaseConnection()->prepare("INSERT INTO subscription(user_id, subscription_frequency, subscription_next_billing, subscription_status, subscription_creation_date)
        VALUES(?, ?, ?, ?)");
        $stmt->bind_param("issss", $userID, $frequency, $db_date, $status,$currentDate);
        $stmt->execute();

        if($stmt->affected_rows > 0)
        {
            return 1;
        }
        else
        {
            return 0;
        }


    }

}
?>