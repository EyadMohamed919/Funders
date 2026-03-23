<?php
require_once __DIR__ . "/../controller/AdminController.php";
if(isset($_POST) && isset($_POST["router"]))
{
    if($_POST["router"] == "add")
    {
        AdminController::addAdmin($_POST["fname"], $_POST["lname"], $_POST["email"],
        $_POST["password"], $_POST["phone"]);
    }
} 
else if(isset($_GET))
{
    if(isset($_GET["delete"]))
    {
        AdminController::deleteAdmin($_GET["id"]);
    }
}
?>