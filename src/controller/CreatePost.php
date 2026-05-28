<?php
require_once __DIR__ . "/../../model/PostModel.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $post = new PostModel();
    $post->title = trim($_POST["title"] ?? "");
    $post->categoryId = intval($_POST["category_id"] ?? 0);
    $post->doneeId = intval($_POST["donee_id"] ?? 0);

    if ($post->createPost()) {
        header("Location: ../../controller/post_router.php?action=index");
        exit;
    } else {
        die("Failed to create post.");
    }
}