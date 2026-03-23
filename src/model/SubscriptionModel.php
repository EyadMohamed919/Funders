<?php

require_once __DIR__ . "/UserModel.php";
require_once __DIR__ . "/../../config/db.php";
class Subscription{

    private $subscriptionID;
    private $frequency;
    private $nextBillingDate;
    private $status;
    private $userID;
    private $amount;
    private $gatewayId;

    public function createSubscription($amount, $frequency, $userID)
    {
        $this->userID = $userID;
        $this->frequency = htmlspecialchars(trim($frequency));
        $this->amount = $amount;
        $this->gatewayId = 0;

        if($this->frequency == "monthly")
        {
            $date = new DateTime();
    
            $date->modify('+30 days');
    
            $this->nextBillingDate = $date->format('Y-m-d');
        }
        else if($this->frequency == "weekly")
        {
            $date = new DateTime();
    
            $date->modify('+7 days');
    
            $this->nextBillingDate = $date->format('Y-m-d');
    
        }
        else
        {
            $date = new DateTime();
    
            $date->modify('+30 days');
    
            $this->nextBillingDate = $date->format('Y-m-d');
        }

        $currentDate = new DateTime();
        $currentDate = $currentDate->format('Y-m-d');
        $this->status = "active";
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("INSERT INTO subscription(user_id, subscription_frequency, 
        subscription_next_billing_date, subscription_status, 
        subscription_creation_date, subscription_gateway_id, subscription_amount)
        VALUES(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssid", $userID, $this->frequency, $this->nextBillingDate, $this->status, $currentDate, $this->gatewayId, $this->amount);
        $stmt->execute();
        

        if($stmt->affected_rows > 0)
        {
            $this->subscriptionID = $conn->insert_id;
            return 1;
        }
        else
        {
            return 0;
        }


    }

}
?>