<?php
require_once __DIR__ . "/../model/CategoryModel.php";
class CategoryController{
    //show list page
    public static function index(){
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAllCategories();
        include __DIR__ . "/../view/categories/index.php";
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
            include __DIR__ . "/../view/category/show.php";
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
        $categoryModel->setName($name);
        $categoryModel->createCategory();
        header("Location: /src/router/CategoryRouter.php");
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
        $categoryModel->setCategory($id, $name);
        $categoryModel->updateCategory();
        header("Location: /src/router/CategoryRouter.php");
        exit();
    }
    //delete category
    public static function delete($id){
        $categoryModel = new CategoryModel();
        $categoryModel->setCategory($id, '');
        $categoryModel->deleteCategory();
        header("Location: /src/router/CategoryRouter.php");
        exit();
    }
}
?>