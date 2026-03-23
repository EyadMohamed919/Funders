<?php
require_once __DIR__ . "/../controller/SubscriptionController.php";
if(isset($_POST))
{
    session_start();
    if(!isset($_SESSION["user_id"]))
    {
        header("location: ../view/layout/Login.php");
    }
    else
    {

        SubscriptionController::subscribe($_POST["amount"], $_POST["frequency"], $_SESSION["user_id"]);
    }
} 
?>