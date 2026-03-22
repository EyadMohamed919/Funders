<?php
require_once("../model/CategoryModel.php");
class CategoryController{
    //show list page
    public static function index(){
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAllCategories();
        include("../view/category/index.php");
    }

    //show single category
    public static function show($id){
        $categoryModel = new CategoryModel();
        $category = $categoryModel->getCategoryById($id);
        if (!$category) {
            echo "Category not found.";
            return;
        }
        else
        {
            include("../view/category/show.php");
        }
    }
    //show create form
    public static function store(){
        $name = $_POST['category_name'] ?? '';
        $name = trim($name);
        if (empty($name)) {
            echo "Category name cannot be empty.";
            return;
        }
        $categoryModel = new CategoryModel();
        $categoryModel->createCategory($name);
        header("Location: /categories");
        exit();
    }
    //update category
    public static function update($id){
        $name = $_POST['category_name'] ?? '';
        $name = trim($name);
        if (empty($name)) {
            echo "Category name cannot be empty.";
            return;
        }
        $categoryModel = new CategoryModel();
        $categoryModel->updateCategory($id, $name);
        header("Location: /categories");
        exit();
    }
    //delete category
    public static function delete($id){
        $categoryModel = new CategoryModel();
        $categoryModel->deleteCategory($id);
        header("Location: /categories");
        exit();
    }
}
?>