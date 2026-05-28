<?php 
require_once __DIR__ . "/DonationModel.php";
require_once __DIR__ . "/IDonationStrategy.php";
class DonationServiceStrategy extends DonationModel implements IDonationStrategy{
    
    private $serviceName;
    private $serviceDetails;
    private $conn;

    public function getDonationID(){ return parent::getDonationID(); }
    public function getPostID(){ return parent::getPostID(); }
    public function getCreatedAt(){ return parent::getCreatedAt(); }
    public function getStatus(){ return parent::getStatus(); }
    public function getDonationType(){ return parent::getDonationType(); }
    public function getUserID(){ return parent::getUserID(); }

    public function getServiceName(){ return $this->serviceName; }
    public function getServiceDetails(){ return $this->serviceDetails; }

    public function __construct()
    {
        parent::__construct();
        $this->conn = parent::getConn();
    }
    public function getDonationByDonationID($donationID)
    {
        parent::getDonationByDonationID($donationID);

        $sql = $this->conn->query("SELECT * FROM donation_service_details WHERE donationID = " . $donationID . " LIMIT 1");
        if($sql->num_rows > 0)
        {
            $row = $sql->fetch_assoc();
            $this->serviceName = $row["serviceName"];
            $this->serviceDetails = $row["serviceDetails"];
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
        $serviceName = $data[1];
        $serviceDetails = $data[2];

        $sql = $this->conn->query("INSERT INTO donation_goods_details(serviceName, donationID, serviceDetails) 
        VALUES ('$serviceName', $donationID, '$serviceDetails')");
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