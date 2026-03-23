<?php
require_once("../../config/db.php");
class DonationModel{
    private $donation_id;
    private $donation_amount;
    private $donation_date;
    private $user_id;
    private $postID;

    public function getDonationId() {return $this->donation_id;}
    public function getDonationAmount() {return $this->donation_amount;}
    public function getDonationDate() {return $this->donation_date;}
    public function getUserId() {return $this->user_id;}
    public function getPostId() {return $this->postID;}

    public function setDonationId($donation_id) {$this->donation_id = htmlspecialchars($donation_id);}
    public function setDonationAmount($donation_amount) {$this->donation_amount = htmlspecialchars($donation_amount);}
    public function setDonationDate($donation_date) {$this->donation_date = htmlspecialchars($donation_date);}
    public function setUserId($user_id) {$this->user_id = htmlspecialchars($user_id);}

    public function setDonation($donation_id, $donation_amount, $donation_date, $user_id) {
        $this->setDonationId($donation_id);
        $this->setDonationAmount($donation_amount);
        $this->setDonationDate($donation_date);
        $this->setUserId($user_id);
        return $this;
    }

    public function getDonation($user_id) {
        $user_id= filter_var($user_id, FILTER_SANITIZE_STRING);
       
        $stmt= getDatabaseConnection()->prepare("SELECT * FROM donation WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDonationByDonationID($id)
    {
        $stmt= getDatabaseConnection()->prepare("SELECT * FROM donation WHERE donation_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $this->setDonation($id, $row["donation_amount"], $row["donation_date"], $row["user_id"]);
        $this->postID = $row["post_id"];
        return $this;

    }
    
    public function getAllDonation() {
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM donation");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function UpdateDonation($donation_id, $donation_amount, $donation_date, $user_id) {
        $stmt = getDatabaseConnection()->prepare("UPDATE donation SET donation_amount = ?, donation_date = ? WHERE donation_id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $donation_amount, $donation_date, $donation_id, $user_id);
        return $stmt->execute();
    }

    public function DeleteDonation($donation_id, $user_id) {
        $stmt = getDatabaseConnection()->prepare("DELETE FROM donation WHERE donation_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $donation_id, $user_id);
        return $stmt->execute();
    }

    public function AddDonation($donation_amount, $donation_date, $postID, $user_id) {
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("INSERT INTO donation (donation_amount, donation_date, post_id, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $donation_amount, $donation_date, $postID, $user_id);
        $stmt->execute();
        if($stmt->affected_rows > 0)
        {
            return $conn->insert_id;
        }

    }



};