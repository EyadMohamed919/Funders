<?php
require_once("../controller/UserController.php");
if(isset($_POST))
{
    if($_POST["router"] == "login")
    {
        UserController::login($_POST["email"], $_POST["password"]); 
    }
} 
?>