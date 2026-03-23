<?php
require_once "adminModel.php";
$model = new Model();

$model->addAdmin($_POST["username"], $_POST["password"]);

header("Location: AdminMangement.php");
?>