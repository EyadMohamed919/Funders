<?php
require_once __DIR__ . "/../../../config/db.php";
class DonationModel{
    private $donationID;
    private $postID;
    private $createdAt;
    private $status;
    private $type;
    private $userID;

    private $conn;

    public function __construct()
    {
        $this->conn = getDatabaseConnection();
    }

    public function getDonationID(){ return $this->donationID; }
    public function getPostID(){ return $this->postID; }
    public function getCreatedAt(){ return $this->createdAt; }
    public function getStatus(){ return $this->status; }
    public function getDonationType(){ return $this->type; }
    // public function getDonationDetailsID(){ return $this->detailsID; }
    public function getUserID(){ return $this->userID; }

    public function getConn()
    {
        return $this->conn;
    }

    public function addDonation($postID, $type, $userID)
    {
        // Status 1 = Pending
        $sql = $this->conn->query("INSERT INTO donation(postID, status, type, userID) 
        VALUES ($postID, 1, $type, $userID)");
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
        $sql = $this->conn->query("SELECT * FROM donation WHERE donationID = " . $donationID . " LIMIT 1");
        if($sql->num_rows > 0)
        {
            $row = $sql->fetch_assoc();
            $this->donationID = $donationID;
            $this->postID = $row["postID"]; 
            $this->createdAt = $row["createdAt"]; 
            $this->status = $row["status"]; 
            $this->userID = $row["userID"]; 
            $this->type = $row["type"]; 
            return $this;
        }
        else
        {
            return null;
        }
    }
}