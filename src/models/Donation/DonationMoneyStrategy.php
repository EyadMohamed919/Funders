<?php 
require_once __DIR__ . "/DonationModel.php";
require_once __DIR__ . "/IDonationStrategy.php";
class DonationMoneyStrategy extends DonationModel implements IDonationStrategy{
    
    private $paymentID;
    private $conn;

    public function getDonationID(){ return parent::getDonationID(); }
    public function getPostID(){ return parent::getPostID(); }
    public function getCreatedAt(){ return parent::getCreatedAt(); }
    public function getStatus(){ return parent::getStatus(); }
    public function getDonationType(){ return parent::getDonationType(); }
    public function getUserID(){ return parent::getUserID(); }
    public function getPaymentID(){ return $this->paymentID; }

    public function __construct()
    {
        parent::__construct();
        $this->conn = parent::getConn();
    }
    public function getDonationByDonationID($donationID)
    {
        parent::getDonationByDonationID($donationID);

        $sql = $this->conn->query("SELECT * FROM donation_money_details WHERE donationID = " . $donationID . " LIMIT 1");
        if($sql->num_rows > 0)
        {
            $row = $sql->fetch_assoc();
            $this->paymentID = $row["paymentID"]; 
            return $this;
        }
        else
        {
            return null;
        }

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