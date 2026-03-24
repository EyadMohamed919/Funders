<?php
require_once __DIR__ . "/../model/UserModel.php";
require_once __DIR__ . "/../model/AdminModel.php";
class UserController{
    public static function login($email, $pass)
    {
        $userModel = new UserModel();
        $admin = new AdminModel();
        $userData = $userModel->getUser($email, $pass);
        if ($userData) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user_id'] = $userData->getId();
            $_SESSION['user_fname'] = $userData->getFname();
            $_SESSION['user_lname'] = $userData->getLname();
            $_SESSION['user_email'] = $userData->getEmail();
            $_SESSION['user_phone'] = $userData->getPhone();
            $_SESSION['LOGIN_ERROR'] = null;

            if($admin->checkAdmin($userData->getId()) == 1)
            {
                header("Location: /src/view/layout/AdminDashboard.php");
            }
            else
            {
                header("Location: /src/view/layout/Dashboard.php");
            }
            
            
            exit();
        } else {
            session_start();
            $_SESSION['LOGIN_ERROR'] = "Incorrect email or password";
            header("location:../view/layout/Login.php");
        }
    }

    public static function Register($fname, $lname, $email, $phone, $password) 
    {
        $userModel = new UserModel();
        $userModel->getUserByEmail($email);
        if(!$userModel->getId())
        {
            $userModel = $userModel->createUser($fname, $lname, $email, $phone, $password);
            var_dump($userModel);
            if($userModel != 0)
            {
                session_start();
                $_SESSION['REGISTER_ERROR'] = "User already exist";
                UserController::login($email, $password);
            }
        }
        else
        {
            session_start();
            $_SESSION['REGISTER_ERROR'] = "User already exist";
            header("location:../view/layout/Register.php");
        }
    }

}
?>