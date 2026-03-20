<?php
require_once("../../config/db.php");

class UserModel {
    private $id;
    private $fname;
    private $lname;
    private $password;
    private $email;
    private $phone;

    public function getId() { return $this->id; }
    public function getFname() { return $this->fname; }
    public function getLname() { return $this->lname; }
    public function getEmail() { return $this->email; }
    public function getPhone() { return $this->phone; }

    public function setFname($fname) { $this->fname = htmlspecialchars(trim($fname)); }
    public function setLname($lname) { $this->lname = htmlspecialchars(trim($lname)); }
    public function setEmail($email) { $this->email = filter_var(trim($email), FILTER_SANITIZE_EMAIL); }
    public function setPhone($phone) { $this->phone = htmlspecialchars(trim($phone)); }
    public function setPassword($password) { $this->password = password_hash($password, PASSWORD_DEFAULT); }

    public function setUser($id, $fname, $lname, $email, $password, $phone) {
        $this->id    = $id;
        $this->fname    = htmlspecialchars(trim($fname));
        $this->lname    = htmlspecialchars(trim($lname));
        $this->email    = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $this->password = $password;
        $this->phone    = htmlspecialchars(trim($phone));
        return $this;
    }

    public function getUser($email, $password) {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM user WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            if (password_verify($password, $row["user_password"])) {
                $user = new self();
                return $user->setUser(
                    $row["user_id"],
                    $row["user_fname"], 
                    $row["user_lname"], 
                    $row["user_email"], 
                    $row["user_password"], 
                    $row["user_phone"]
                );
            }
        }
        return false;
    }

    public function getAllUsers() {
        $userArray = array();
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM user");
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $user = new self();
            $user->setUser(
                $row["user_id"],
                $row["user_fname"], 
                $row["user_lname"], 
                $row["user_email"], 
                $row["user_password"], 
                $row["user_phone"]
            );
            $userArray[] = $user;
        }

        return !empty($userArray) ? $userArray : false;
    }

    public function updateUser($id, $fname, $lname, $email, $phone, $password) {
        $fname = htmlspecialchars(trim($fname));
        $lname = htmlspecialchars(trim($lname));
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = getDatabaseConnection()->prepare("UPDATE user SET 
            user_fname = ?, user_lname = ?, user_email = ?, user_password = ?, user_phone = ?
            WHERE user_id = ?");
            
        $stmt->bind_param("sssssi", $fname, $lname, $email, $hashedPassword, $phone, $id);
        $stmt->execute();
        
        return $stmt->affected_rows > 0;
    }
}