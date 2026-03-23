<?php
require_once __DIR__ . "/../../config/db.php";

class CategoryModel {
    private $id;
    private $name;

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }

    public function setName($name) { $this->name = htmlspecialchars(trim($name)); }

    public function setCategory($id, $name) {
        $this->id   = $id;
        $this->name = htmlspecialchars(trim($name));
        return $this;
    }
    public function getCategory($id) {
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM category WHERE category_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $this->setCategory(
                $row["category_id"],
                $row["category_name"]
            );
        }
        return false;
    }

    public function getAllCategories() {
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM category");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $categories = [];
            while ($row = $result->fetch_assoc()) {
                $category = new self();
                $categories[] = $category->setCategory(
                    $row["category_id"],
                    $row["category_name"]
                );
            }
            return $categories;
        }
        return false;
    }
    public function createCategory() {
        $stmt = getDatabaseConnection()->prepare("INSERT INTO category (category_name) VALUES (?)");
        $stmt->bind_param("s", $this->name);
        return $stmt->execute();
    }

    public function updateCategory() {
        $stmt = getDatabaseConnection()->prepare("UPDATE category SET category_name = ? WHERE category_id = ?");
        $stmt->bind_param("si", $this->name, $this->id);
        return $stmt->execute();
    }

    public function deleteCategory() {
        $stmt = getDatabaseConnection()->prepare("DELETE FROM category WHERE category_id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}
?>