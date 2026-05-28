<?php
require_once __DIR__ . "/../../model/PostModel.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $post = new PostModel();
    $post->id = intval($_POST["post_id"] ?? 0);
    $post->title = trim($_POST["title"] ?? "");
    $post->currentAmount = floatval($_POST["current_amount"] ?? 0);
    $post->categoryId = intval($_POST["category_id"] ?? 0);
    $post->doneeId = intval($_POST["donee_id"] ?? 0);

    if ($post->updatePost()) {
        header("Location: ../../controller/post_router.php?action=show&id=" . $post->id);
        exit;
    } else {
        die("Failed to update post.");
    }
}