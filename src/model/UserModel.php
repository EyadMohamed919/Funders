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
            var_dump($this->setUser($row["user_fname"], $row["user_lname"], $row["user_email"], $row["user_password"], $row["user_phone"]));
        }
        else
        {
            return 0;
        }
    }
} 

$user = new UserModel();
$user->getUser("eyad@gmail.com", 1234);
?>