<?php
include_once "adminModel.php";
include_once "adminView.php";

$model = new Model();
$view  = new AdminView();

// only fetch & display
$data = $model->getAll();
$view->showAll($data);
$cmd = $_GET["Command"];

if ($cmd == "Add") {
    $model->addAdmin($_POST["username"], $_POST["password"]);
}

if ($cmd == "Delete") {
    $model->deleteAdmin($_GET["username"]);
}

$admins = $model->getAdmins();
?>