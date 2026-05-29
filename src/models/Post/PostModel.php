<?php
require_once __DIR__ . "/../../../config/db.php";
require_once __DIR__ . "/../Post/IPost.php";

class PostModel implements IPost {
    
    public int $id;
    public int $user_id;
    public int $categoryId;
    public string $title;
    public bool $featured;
    public bool $urgent;
    public $details;

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->user_id; }
    public function getCategoryId(): int { return $this->categoryId; }
    public function getTitle(): string { return $this->title; }
    public function isFeatured(): bool { return $this->featured; }
    public function isUrgent(): bool { return $this->urgent; }
    public function getDetails() { return $this->details; }

    public function __construct(
        int $id = 0,
        int $user_id = 0,
        int $categoryId = 0,
        string $title = "",
        bool $featured = false,
        bool $urgent = false,
        $details = ""
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->categoryId = $categoryId;
        $this->title = $title;
        $this->featured = $featured;
        $this->urgent= $urgent;
        $this->details = $details;
    }

    public function displayPost(): string {
        return "<h3>{$this->title}</h3>";
    }


    public function getPostById(int $id): ?self {
        $conn = getDatabaseConnection();
        $result = $conn->query("SELECT * FROM post WHERE post_id = $id");

        if ($row = $result->fetch_assoc()) {
            return new self(
                (int)$row["post_id"],
                (int)($row["user_id"] ?? 0),
                (int)$row["category_id"],
                $row["title"] ?? "",
                (bool)($row["featured"] ?? 0),
                (bool)($row["urgent"] ?? 0),
                $row["details"]
            );
        }
        return null;
    }

    public function getAllPosts(): array {
        $posts = [];
        $conn = getDatabaseConnection();
        $result = $conn->query("SELECT * FROM post");

        while ($row = $result->fetch_assoc()) {
            $posts[] = new self(
                (int)$row["post_id"],
                (int)($row["user_id"] ?? 0),
                (int)$row["category_id"],
                $row["title"] ?? "",
                (bool)($row["featured"] ?? 0),
                (bool)($row["urgent"] ?? 0),
                $row["details"]   
            );
        }
        return $posts;
    }

    public function createPost(): bool {
        $conn = getDatabaseConnection();
        $title = $conn->real_escape_string($this->title);
        $featuredInt = (int)$this->featured;
        $urgentInt = (int)$this->urgent;
        $details = $this->details;
        
        $result = $conn->query(
            "INSERT INTO post (title, category_id, user_id, featured, urgent, details) 
             VALUES ('$title', {$this->categoryId}, {$this->user_id}, $featuredInt, $urgentInt, '$details')"  
        );
        return $result !== false;
    }

    public function updatePost(): bool {
        $conn = getDatabaseConnection();
        $title = $conn->real_escape_string($this->title);
        $featuredInt = (int)$this->featured;  
        $urgentInt = (int)$this->urgent;
        $details = $this->details;
        
        $result = $conn->query(
            "UPDATE post 
             SET title = '$title', 
                 category_id = {$this->categoryId}, 
                 user_id = {$this->user_id},
                 featured = $featuredInt
                 urgent = $urgentInt,
                 details = '$details'
             WHERE post_id = {$this->id}"
        );
        return $result !== false;
    }
}