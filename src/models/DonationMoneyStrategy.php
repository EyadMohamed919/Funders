<?php 
require_once __DIR__ . "/DonationModel.php";
class DonationMoneyStrategy extends DonationModel implements IDonationStrategy{
    
    private $paymentID;
    private $donationID;
    private $conn;
    public function __construct()
    {
        parent::__construct();
        $this->conn = parent::getConn();
    }
    public function processDonation($data)
    {   
        $postID = $data[0];
        $typeID = $data[1];
        $paymentID = $data[2];
        $donationID = parent::addDonation($postID, $typeID);

        
        $sql = $this->conn->query("INSERT INTO donation_money_details(paymentID, donationID) 
        VALUES (" . $paymentID .", " . $donationID . ")");
        if($this->conn->affected_rows > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}