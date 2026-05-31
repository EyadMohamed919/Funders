<?php
require_once __DIR__ . "/../../../config/db.php";
require_once __DIR__ . "/../Post/IPost.php";

class PostModel implements IPost {
    
    public $id;
    public $user_id;
    public $categoryId;
    public $title;
    public $featured;
    public $urgent;
    public $details;
    public $targetAmount;

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->user_id; }
    public function getCategoryId(): int { return $this->categoryId; }
    public function getTitle(): string { return $this->title; }
    public function isFeatured(): bool { return $this->featured; }
    public function isUrgent(): bool { return $this->urgent; }
    public function getDetails() { return $this->details; }
    public function getTargetAmount() { return $this->targetAmount; }
    public function __construct(
        $id = 0,
        $user_id = 0,
        $categoryId = 0,
        $title = "",
        $featured = false,
        $urgent = false,
        $details = "",
        $targetAmount = 0) 
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->categoryId = $categoryId;
        $this->title = $title;
        $this->featured = $featured;
        $this->urgent= $urgent;
        $this->details = $details;
        $this->targetAmount = $targetAmount;
    }

    public function displayPost() {
        return "<h3>{$this->title}</h3>";
    }


    public function getPostById(int $id) {
        $conn = getDatabaseConnection();
        $result = $conn->query("SELECT * FROM post WHERE post_id = $id");

        if ($row = $result->fetch_assoc()) {
            return new self(
                $row["post_id"],
                $row["user_id"],
                $row["category_id"],
                $row["title"],
                $row["featured"],
                $row["urgent"],
                $row["details"],
                $row["targetAmount"]
            );
        }
        return null;
    }

    public function getAllPosts() {
        $posts = [];
        $conn = getDatabaseConnection();
        $result = $conn->query("SELECT * FROM post");

        while ($row = $result->fetch_assoc()) {
            $posts[] = new self(
                $row["post_id"],
                $row["user_id"],
                $row["category_id"],
                $row["title"],
                $row["featured"],
                $row["urgent"],
                $row["details"], 
                $row["targetAmount"] 
            );
        }
        return $posts;
    }

    public function createPost(){
        $conn = getDatabaseConnection();
        $title = $conn->real_escape_string($this->title);
        $featuredInt = (int)$this->featured;
        $urgentInt = (int)$this->urgent;
        $details = $this->details;
        $targetAmount = $this->targetAmount;
        
        $result = $conn->query(
            "INSERT INTO post (title, category_id, user_id, featured, urgent, details, targetAmount) 
             VALUES ('$title', {$this->categoryId}, {$this->user_id}, $featuredInt, $urgentInt, '$details', $targetAmount)"  
        );
        return $result != false;
    }

    public function updatePost() {
        $conn = getDatabaseConnection();
        $title = $conn->real_escape_string($this->title);
        $featuredInt = (int)$this->featured;  
        $urgentInt = (int)$this->urgent;
        $details = $this->details;
        $targetAmount = $this->targetAmount;
        $result = $conn->query(
            "UPDATE post 
             SET title = '$title', 
                 category_id = {$this->categoryId}, 
                 user_id = {$this->user_id},
                 featured = $featuredInt
                 urgent = $urgentInt,
                 details = '$details'
                 targetAmount = $targetAmount
             WHERE post_id = {$this->id}"
        );
        return $result != false;
    }
}