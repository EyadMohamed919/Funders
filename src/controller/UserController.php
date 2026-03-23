<?php
require_once("../model/UserModel.php");
class UserController{
    public static function login()
    {
        $email = $_POST['email'] ?? '';
        $pass  = $_POST['password'] ?? '';

        $userModel = new UserModel();
        $userData = $userModel->getUser($email, $pass);

        if ($userData) {
            session_start();
            $_SESSION['user_id'] = $userData->getId();
            $_SESSION['user_fname'] = $userData->getFname();
            $_SESSION['user_lname'] = $userData->getLname();
            $_SESSION['user_email'] = $userData->getEmail();
            $_SESSION['user_phone'] = $userData->getPhone();
            $_SESSION['LOGIN_ERROR'] = null;
            
            header("Location: /src/view/layout/Dashboard.php");
            exit();
        } else {
            session_start();
            $_SESSION['LOGIN_ERROR'] = "Incorrect email or password";
            header("location:../view/layout/login.php");
        }
    }

}
?>