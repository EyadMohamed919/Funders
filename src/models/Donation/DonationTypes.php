<?php
require_once __DIR__ . "/../../config/db.php";
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
}
?>