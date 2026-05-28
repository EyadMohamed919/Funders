<?php
require_once __DIR__ . "/../controllers/UserController.php";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(isset($_POST["registerUser"]))
	{
		UserController::register();
	}
	else if(isset($_POST["loginUser"]))
	{
		UserController::login();
	}
	else if(isset($_POST["updateMyAttribute"]))
	{
		UserController::updateMyAttribute();
	}
	else if(isset($_POST["requestVerification"]))
	{
		UserController::requestVerification();
	}
}
else if($_SERVER["REQUEST_METHOD"] == "GET")
{
	if(isset($_GET["action"]) && $_GET["action"] == "my_profile")
	{
		UserController::getMyProfile();
	}
}

?>