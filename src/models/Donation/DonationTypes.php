<?php
require_once __DIR__ . "/../../../config/db.php";
class DonationTypes{
    private $donationTypes;
    private $conn;

    public function __construct() {
        $this->donationTypes = [];
        $this->conn = getDatabaseConnection();
    }

    public function getAllDonationTypes()
    {
        $sql = $this->conn->query("SELECT * FROM donation_types");
        if($sql->num_rows > 0)
        {
            while($row = $sql->fetch_assoc())
            {
                $this->donationTypes[$row["typeID"]] = $row["typeName"];
            }

            return $this->donationTypes;
        }
        else
        {
            return [];
        }
    }

    public function getDonationTypeName($typeID)
    {
        $sql = $this->conn->query("SELECT * FROM donation_types WHERE typeID = " . $typeID);
        if($sql->num_rows > 0)
        {
            $row = $sql->fetch_assoc();

            return $row["typeName"];
        }
        else
        {
            return null;
        }
    }
}
?>