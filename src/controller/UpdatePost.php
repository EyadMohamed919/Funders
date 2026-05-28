<?php
require_once __DIR__ . "/../../model/PostModel.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $post = new PostModel();
    $post->id = intval($_POST["post_id"] ?? 0);
    $post->title = trim($_POST["title"] ?? "");
    $post->categoryId = intval($_POST["category_id"] ?? 0);
    $post->doneeId = intval($_POST["donee_id"] ?? 0);
    $post->featured = intval($_POST["feature"] ?? 0);
    $post->urgent = intval($_POST["urgent"] ?? 0);

    if ($post->updatePost()) {
        header("Location: ../../controller/post_router.php?action=show&id=" . $post->id);
        exit;
    } else {
        die("Failed to update post.");
    }
}