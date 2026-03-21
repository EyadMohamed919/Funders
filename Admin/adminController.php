<?php
include_once "adminModel.php";
include_once "adminView.php";

$model = new Model();
$view  = new AdminView();

// only fetch & display
$data = $model->getAll();
$view->showAll($data);
?>