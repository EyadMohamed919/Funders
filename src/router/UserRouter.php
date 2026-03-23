<?php
require_once("../controller/UserController.php");
if(isset($_POST) && isset($_POST["router"]))    
{
    if($_POST["router"] == "login")
    {
        UserController::login($_POST["email"], $_POST["password"]); 
    }
} 
else if(isset($_GET))
{
    if(isset($_GET["logout"]))
    {
        session_destroy();
        header("location: ../view/layout/Login.php");
    }
}
?>