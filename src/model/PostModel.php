<?php
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../view/posts/IPost.php";

class PostModel implements IPost {
    
    public int $id;
    public int $doneeId;
    public int $categoryId;
    public string $title;
    public float $currentAmount;

    public function __construct(
        int $id = 0,
        int $doneeId = 0,
        int $categoryId = 0,
        string $title = "",
        float $currentAmount = 0.0
    ) {
        $this->id = $id;
        $this->doneeId = $doneeId;
        $this->categoryId = $categoryId;
        $this->title = $title;
        $this->currentAmount = $currentAmount;
    }


    public function displayPost(): string {
        return "<h3>{$this->title}</h3>"
             . "<p>Raised: $" . number_format($this->currentAmount, 2) . "</p>";
    }

    public function getPostById(int $id): ?self {
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("SELECT * FROM post WHERE post_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return new self(
                (int)$row["post_id"],
                (int)($row["donee_id"] ?? 0),
                (int)$row["category_id"],
                $row["title"] ?? "",
                (float)$row["currentAmount"]
            );
        }
        return null;
    }

    public function getAllPosts(): array {
        $posts = [];
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("SELECT * FROM post");
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $posts[] = new self(
                (int)$row["post_id"],
                (int)($row["donee_id"] ?? 0),
                (int)$row["category_id"],
                $row["title"] ?? "",
                (float)$row["currentAmount"]
            );
        }
        return $posts;
    }

    public function createPost(): bool {
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare(
            "INSERT INTO post (title, currentAmount, category_id, donee_id) 
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("sdii", $this->title, $this->currentAmount, $this->categoryId, $this->doneeId);
        return $stmt->execute();
    }

    public function updatePost(): bool {
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare(
            "UPDATE post 
             SET title = ?, currentAmount = ?, category_id = ?, donee_id = ? 
             WHERE post_id = ?"
        );
        $stmt->bind_param("sdiii", $this->title, $this->currentAmount, $this->categoryId, $this->doneeId, $this->id);
        return $stmt->execute();
    }

    public function deletePost(): bool {
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("DELETE FROM post WHERE post_id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}