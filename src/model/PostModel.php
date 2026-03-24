<?php

require_once __DIR__ . "/../../config/db.php";

class PostModel {
    private $id;
    private $title;
    private $description;
    private $target_amount;
    private $current_amount;
    private $status;
    private $image_path;
    private $category_id;

    public function getId() { 
        return $this->id; 
        }
    public function getTitle() { 
        return $this->title; 
        }
    public function getDescription() { 
        return $this->description; 
        }
    public function getTargetAmount() { 
        return $this->target_amount; 
        }
    public function getCurrentAmount() { 
        return $this->current_amount; 
        }
    public function getStatus() { 
        return $this->status; 
        }
    public function getImagePath() { 
        return $this->image_path; 
        }
    public function getCategoryId() { 
        return $this->category_id; 
        }

    public function setId($id) { 
        $this->id = intval($id); 
        }
    public function setTitle($title) { 
        $this->title = htmlspecialchars(trim((string)($title ?? ''))); 
        }
    public function setDescription($description) { 
        $this->description = htmlspecialchars(trim((string)($description ?? ''))); 
        }
    public function setTargetAmount($target_amount) { 
        $this->target_amount = floatval($target_amount ?? 0); 
        }
    
    public function setCurrentAmount($current_amount) { 
        $this->current_amount = floatval($current_amount ?? 0); 
        }
    public function setStatus($status) { 
        $this->status = htmlspecialchars(trim((string)($status ?? ''))); 
        }
    public function setImagePath($image_path) { 
        $this->image_path = htmlspecialchars(trim((string)($image_path ?? ''))); 
        }
    public function setCategoryId($category_id) { 
        $this->category_id = intval($category_id ?? 0); 
        }

    public function setPost($id, $title, $description, $target_amount, $current_amount, $status, $image_path, $category_id) {

        $this->id = intval($id);
        $this->title = htmlspecialchars(trim((string)($title ?? '')));
        $this->description = htmlspecialchars(trim((string)($description ?? '')));
        $this->target_amount = floatval($target_amount ?? 0);
        $this->current_amount = floatval($current_amount ?? 0);
        $this->status = htmlspecialchars(trim((string)($status ?? '')));
        $this->image_path = htmlspecialchars(trim((string)($image_path ?? '')));
        $this->category_id = intval($category_id ?? 0);
        return $this;
    }
    
    public function getPostById($id) {
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM post WHERE post_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $this->setPost(
                $row["post_id"],
                $row["post_title"],
                $row["post_description"],
                $row["post_target_amount"],
                $row["post_current_amount"],
                $row["post_status"],
                $row["post_image_path"],
                $row["category_id"]
            );
        }
        return false;
    }

   public function getAllPosts() {
        $posts = [];
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM post");
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $post = new self();
            $posts[] = $post->setPost(
                $row["post_id"],
                $row["post_title"],
                $row["post_description"],
                $row["post_target_amount"],
                $row["post_current_amount"],
                $row["post_status"],
                $row["post_image_path"],
                $row["category_id"]
            );
        }
        return $posts;
    }

    public function createPost() {
        $stmt = getDatabaseConnection()->prepare("INSERT INTO post (post_title, post_description, post_target_amount, post_current_amount, post_status, post_image_path, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssddssi", $this->title, $this->description, $this->target_amount, $this->current_amount, $this->status, $this->image_path, $this->category_id);
        return $stmt->execute();
    }

    public function updatePost() {
        $stmt = getDatabaseConnection()->prepare("UPDATE post SET post_title = ?, post_description = ?, post_target_amount = ?, post_current_amount = ?, post_status = ?, post_image_path = ?, category_id = ? WHERE post_id = ?");
        $stmt->bind_param("ssddssii", $this->title, $this->description, $this->target_amount, $this->current_amount, $this->status, $this->image_path, $this->category_id, $this->id);
        return $stmt->execute();
    }

    public function deletePost() {
        $stmt = getDatabaseConnection()->prepare("DELETE FROM post WHERE post_id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
    public function getPostsByCategoryId($category_id) {
        $posts = [];
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM post WHERE category_id = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $post = new self();
            $posts[] = $post->setPost(
                $row["post_id"],
                $row["post_title"],
                $row["post_description"],
                $row["post_target_amount"],
                $row["post_current_amount"],
                $row["post_status"],
                $row["post_image_path"],
                $row["category_id"]
            );
        }
        return !empty($posts) ? $posts : false;
    }

    public function changeStatus($state, $postID)
    {
        $stmt = getDatabaseConnection()->prepare("UPDATE post SET post_status = ? WHERE post_id = ?");
        $stmt->bind_param("si", $state, $postID);
        $stmt->execute();
        
        if($stmt->affected_rows > 0)
        {
            return 1;
        }
        else
        {
            return 0;
        }

    }

    public function getCalculateAmount($postID)
    {
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("SELECT SUM(donation_amount) AS donation_amount 
        FROM donation WHERE post_id = ?;"); 
        $stmt->bind_param("i", $postID);
        $stmt->execute();

        $result = $stmt->get_result();
        if($result->num_rows > 0)
        {
            $amount = $result->fetch_assoc()["donation_amount"];
            $this->current_amount = $amount;
            return $amount;
        }
        else
        {
            return 0;
        }
    }
}


?>