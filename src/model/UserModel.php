<?php
require_once("../../config/db.php");
class UserModel{
    private $fname;
    private $lname;
    private $password;
    private $email;
    private $phone;

    public function __construct()
    {
    }

    public function setUser($fname, $lname, $password, $email, $phone)
    {
        $this->fname = $fname;
        $this->lname = $lname;
        $this->password = $password;
        $this->email = $email;
        $this->phone = $phone;
        return $this;
    }

    public function getUser($email, $password)
    {
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM user WHERE user_email = ? AND user_password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            return $this->setUser($row["user_fname"], $row["user_lname"], $row["user_email"], $row["user_password"], $row["user_phone"]);
        }
        else
        {
            return 0;
        }
    }

    public function getAllUsers()
    {
        $userArray = array();
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM user");
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $user = $this->setUser($row["user_fname"], $row["user_lname"], $row["user_email"], $row["user_password"], $row["user_phone"]);
                array_push($userArray, $user);
            }
        }
        else
        {
            return 0;
        }

        return $userArray;
    }

    public function updateUser($id, $fname, $lname, $email, $phone, $password)
    {
        $stmt = getDatabaseConnection()->prepare("UPDATE user SET 
        user_fname = ?, user_lname = ?, user_email = ?, user_password = ?, user_phone = ?
        WHERE user_id = ?");
        $stmt->bind_param("ssssii", $fname, $lname, $email, $password, $phone, $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) 
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
} 

// TESTING CODE
// $user = new UserModel();
// var_dump($user->updateUser(2, "Hamada", "Mohamed", "hamada.mohamed@gmail.com", 115487653, "123"));
?>