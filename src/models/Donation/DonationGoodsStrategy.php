<?php 
require_once __DIR__ . "/DonationModel.php";
require_once __DIR__ . "/IDonationStrategy.php";
class DonationGoodsStrategy extends DonationModel implements IDonationStrategy{
    
    private $weight;
    private $goodName;
    private $goodDetails;
    private $conn;

    public function getDonationID(){ return parent::getDonationID(); }
    public function getPostID(){ return parent::getPostID(); }
    public function getCreatedAt(){ return parent::getCreatedAt(); }
    public function getStatus(){ return parent::getStatus(); }
    public function getDonationType(){ return parent::getDonationType(); }
    public function getUserID(){ return parent::getUserID(); }
    public function getWeight(){ return $this->weight; }
    public function getGoodName(){ return $this->goodName; }
    public function getGoodDetails(){ return $this->goodDetails; }

    public function __construct()
    {
        parent::__construct();
        $this->conn = parent::getConn();
    }
    public function getDonationByDonationID($donationID)
    {
        parent::getDonationByDonationID($donationID);

        $sql = $this->conn->query("SELECT * FROM donation_goods_details WHERE donationID = " . $donationID . " LIMIT 1");
        if($sql->num_rows > 0)
        {
            $row = $sql->fetch_assoc();
            $this->weight = $row["weight"]; 
            $this->goodName = $row["goodsName"];
            $this->goodDetails = $row["goodsDetails"];
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
        $weight = $data[1];
        $goodsName = $data[2];
        $goodsDetails = $data[3];

        $sql = $this->conn->query("INSERT INTO donation_goods_details(weight, donationID, goodsName, goodsDetails) 
        VALUES ($weight, $donationID, '$goodsName', '$goodsDetails')");
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