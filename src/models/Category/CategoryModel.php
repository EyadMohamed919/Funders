<?php
require_once __DIR__ . "/../../../config/db.php";
class CategoryModel{
    
    private $conn;
    public function __construct()
    {
        $this->conn = getDatabaseConnection();
    }

    public function getAllCategories()
    {
        $categories = [];
        $sql = $this->conn->query("SELECT * FROM category");
        if($sql->num_rows > 0)
        {
            while($row = $sql->fetch_assoc())
            {
                array_push($categories, $row);
            }
            return $categories;
        }
        else
        {
            return [];
        }
    }

    public function getCategoryNameByCategoryID($categoryID)
    {
        $sql = $this->conn->query("SELECT * FROM category WHERE category_id = " . $categoryID);
        if($sql->num_rows > 0)
        {
            return $sql->fetch_assoc()["category_details"];
        }
        else
        {
            return null;
        }
    }
}