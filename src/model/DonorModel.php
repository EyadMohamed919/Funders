<?php

require_once __DIR__ . "/UserModel.php";
require_once __DIR__ . "/../../config/db.php";
class DonorModel extends UserModel{
    
    private $donorID;
    private $isLaundering;
    private $totalDonatedAmount;
    private $isAnonymous;
    
    public function createDonorAccount($userID)
    {
        $this->isLaundering = false;
        $this->isAnonymous = false;

        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("INSERT INTO donor(donor_laundering, donor_anonymous, user_id)
        VALUES(?, ?, ?)");
        $stmt->bind_param("iii", $this->isLaundering, $this->isAnonymous);
        $stmt->execute();
        
        if($stmt->affected_rows > 0)
        {
            $this->donorID = $conn->insert_id;
            return 1;    
        }
        else
        {
            return 0; 
        }
    }

    public function toggleAnonymity($state)
    {
        $stmt = getDatabaseConnection()->prepare("UPDATE donor SET donor_anonymous = ?");
        $stmt->bind_param("i", $state);
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


    public function setLaundering($state)
    {
        $stmt = getDatabaseConnection()->prepare("UPDATE donor SET donor_laundering = ?");
        $stmt->bind_param("i", $state);
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