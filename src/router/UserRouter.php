<?php
require_once("../controller/UserController.php");
if(isset($_POST) && isset($_POST["router"]))    
{
    if($_POST["router"] == "login")
    {
        UserController::login($_POST["email"], $_POST["password"]); 
    }
    else if($_POST["router"] == "register")
    {
        UserController::register($_POST["fname"], $_POST["lname"], $_POST["email"], $_POST["phone"],$_POST["password"]);
    }
} 
else if(isset($_GET))
{
    if(isset($_GET["logout"]))
    {
        session_start();
        session_destroy();
        header("location: ../view/layout/Login.php");
    }
}
?>