<?php
require_once __DIR__ . "/../../model/PostModel.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if(isset($_POST["addPost"]))
    {
        $post = new PostModel();
        $post->title = $_POST["title"];
        $post->categoryId = $_POST["category_id"];
        $post->doneeId = $_POST["donee_id"];
        $post->featured = $_POST["feature"];
        $post->urgent = $_POST["urgent"];
    
        if ($post->createPost()) {
            header("Location: ../../index.php");
            exit;
        } else {
            die("Failed to create post.");
        }
    }
    else if(isset($_POST["editPost"]))
    {
        $post = new PostModel();
        $post->id = $_POST["post_id"];
        $post->title = $_POST["title"];
        $post->categoryId = $_POST["category_id"];
        $post->doneeId = $_POST["donee_id"];
        $post->featured = $_POST["feature"];
        $post->urgent = $_POST["urgent"];
    
        if ($post->updatePost()) {
            header("Location: ../../index.php");
            exit;
        } else {
            die("Failed to update post.");
        }
    }
}