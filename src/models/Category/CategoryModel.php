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
}