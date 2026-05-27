<?php 
require_once __DIR__ . "/../../config/db.php";
class DonationStatus{
    private $statusID;
    private $statusName;
    private $conn;
    public function __construct()
    {
        $this->conn = getDatabaseConnection();
    }


    public function getAllDonationStatus()
    {
        $statusArray = [];
        $sql = $this->conn->query("SELECT * FROM donation_status");
        if($sql->num_rows > 0)
        {
            while($row = $sql->fetch_assoc())
            {
                $statusArray[$row["statusID"]] = $row["statusName"];
            }
            return $statusArray;
        }
        else
        {
            return $statusArray;
        }
    }
}