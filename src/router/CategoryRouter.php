<?php
reuire_once("../controller/CategoryController.php");
if(isset($_POST))
{
    if($_POST["router"] == "getAllCategories")
    {
        CategoryController::getAllCategories();
    }
}
?>