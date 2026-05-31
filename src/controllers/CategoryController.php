<?php 
require_once __DIR__ . "/../models/Category/CategoryModel.php";
class CategoryController{
    public static function getAllCategories()
    {
        $category = new CategoryModel();
        return $category->getAllCategories();
    }

    public static function getCategoryName($categoryID)
    {
        $category = new CategoryModel();
        return $category->getCategoryNameByCategoryID($categoryID);
    }
}