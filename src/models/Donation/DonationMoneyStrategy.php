<?php 
require_once __DIR__ . "/DonationModel.php";
require_once __DIR__ . "/IDonationStrategy.php";
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
        $donationID = $data[0];
        $paymentID = $data[1];

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