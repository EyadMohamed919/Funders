<?php 
require_once __DIR__ . "/../model/AdminModel.php";
require_once __DIR__ . "/../model/UserModel.php";
class AdminController{
    public static function checkAdmin($userID)
    {
        $admin = new AdminModel();

        return $admin->checkAdmin($userID);
    }

    public static function getAllAdmin()
    {
        $admin = new AdminModel();
        
        return $admin->getAllAdmin();
    } 

    public static function addAdmin($fname, $lname, $email, $password, $phone)
    {

        $user = new UserModel();
        $userID = $user->createUser($fname, $lname, $email, $password, $phone);
        if($userID != 0)
        {
            $admin = new AdminModel();
            $admin->addAdmin($userID);
            header("location: ../view/layout/AdminManagement.php");
        }
    }

    public static function deleteAdmin($userID)
    {
        $admin = new AdminModel();
        $admin->deleteAdmin($userID);
        header("location: ../view/layout/AdminManagement.php");
        
    } 
}
?>