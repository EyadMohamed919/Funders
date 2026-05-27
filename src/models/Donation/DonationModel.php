<?php
require_once __DIR__ . "/../../config/db.php";
class DonationModel{
    private $donationID;
    private $postID;
    private $createdAt;
    private $status;
    private $type;
    private $detailsID;

    protected $conn;

    public function __construct()
    {
        $this->conn = getDatabaseConnection();
    }

    public function getConn()
    {
        return $this->conn;
    }

    public function addDonation($postID, $type)
    {
        $sql = $this->conn->query("INSERT INTO donation(postID, status, type) 
        VALUES (" . $postID .", 1, " . $type . ")");
        if($this->conn->affected_rows > 0)
        {
            return $this->conn->insert_id;
        }
        else
        {
            return null;
        }
    }

    public function getDonationByDonationID($donationID)
    {
        $sql = "SELECT * FROM donation WHERE donationID = " . $donationID . " LIMIT 1";
        if($sql->num_rows > 0)
        {
            $this->donationID = $donationID;
            $this->postID = 
        }
        else
        {
            return null
        }
    }
}