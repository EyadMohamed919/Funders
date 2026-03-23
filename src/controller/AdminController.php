<?php 
require_once __DIR__ . "/../model/AdminModel.php";
require_once __DIR__ . "/../model/UserModel.php";
class AdminController{
    public static function getAllAdmin()
    {
        $admin = new AdminModel();
        
        return $admin->getAllAdmin();
    } 

    public static function addAdmin()
    {
        $user = new UserModel();
        $user = $user->add
    }
}
?>