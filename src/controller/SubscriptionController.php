<?php 
require_once("../model/SubscriptionModel.php");

class SubscriptionController{
    public static function subscribe($amount, $frequency, $userID)
    {
        $subscription = new Subscription();
        $subscription = $subscription->createSubscription($amount, $frequency, $userID);
        
        if($subscription = 1)
        {
            header("location: ../view/subscription/approved.php");
        }
        else
        {
            echo "Failed to subscribe";
        }
    }
}
?>