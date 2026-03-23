<?php
require_once "adminModel.php";
$model = new Model();

$model->deleteAdmin($_GET["username"]);

header("Location: AdminMangement.php");
?>