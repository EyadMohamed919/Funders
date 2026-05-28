<?php
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../view/posts/IPost.php";

class PostModel implements IPost {
    
    public int $id;
    public int $doneeId;
    public int $categoryId;
    public string $title;
    public bool $featured;
    public bool $urgent;

    public function __construct(
        int $id = 0,
        int $doneeId = 0,
        int $categoryId = 0,
        string $title = "",
        bool $featured = false,
        bool $urgent = false
    ) {
        $this->id = $id;
        $this->doneeId = $doneeId;
        $this->categoryId = $categoryId;
        $this->title = $title;
        $this->featured = $featured;
        $this->urgent= $urgent;
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
                (int)($row["donee_id"] ?? 0),
                (int)$row["category_id"],
                $row["title"] ?? "",
                (bool)($row["featured"] ?? 0),
                (bool)($row["urgent"] ?? 0)
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
                (int)($row["donee_id"] ?? 0),
                (int)$row["category_id"],
                $row["title"] ?? "",
                (bool)($row["featured"] ?? 0),
                (bool)($row["urgent"] ?? 0)   
            );
        }
        return $posts;
    }

    public function createPost(): bool {
        $conn = getDatabaseConnection();
        $title = $conn->real_escape_string($this->title);
        $featuredInt = (int)$this->featured;
        $urgentInt = (int)$this->urgent;
        
        
        $result = $conn->query(
            "INSERT INTO post (title, category_id, donee_id, featured, urgent) 
             VALUES ('$title', {$this->categoryId}, {$this->doneeId}, $featuredInt, $urgentInt)"  
        );
        return $result !== false;
    }

    public function updatePost(): bool {
        $conn = getDatabaseConnection();
        $title = $conn->real_escape_string($this->title);
        $featuredInt = (int)$this->featured;  
        $urgentInt = (int)$this->urgent;
        
        $result = $conn->query(
            "UPDATE post 
             SET title = '$title', 
                 category_id = {$this->categoryId}, 
                 donee_id = {$this->doneeId},
                 featured = $featuredInt
                 urgent = $urgentInt
             WHERE post_id = {$this->id}"
        );
        return $result !== false;
    }
}