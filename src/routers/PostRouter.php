<?php
require_once __DIR__ . "/../models/Post/PostModel.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if(isset($_POST["addPost"]))
    {
        $post = new PostModel();
        $post->title = $_POST["title"];
        $post->categoryId = $_POST["category_id"];
        $post->user_id = (int) $_POST["donee_id"];
        if(!isset($_POST["featured"]))
        {
            $post->featured = false;
        }
        else
        {
            $post->featured = $_POST["featured"];
        }

        if(!isset($_POST["urgent"]))
        {
            $post->featured = false;
        }
        else
        {
            $post->featured = $_POST["urgent"];
        }
        $post->details = $_POST["postDetails"];
        $post->targetAmount = $_POST["targetAmount"];
    
        if ($post->createPost()) {
            header("Location: ../../index.php");
            exit;
        } else {
            header("Location: ../../index.php");
        }
    }
    else if(isset($_POST["editPost"]))
    {
        $post = new PostModel();
        $post->id = $_POST["post_id"];
        $post->title = $_POST["title"];
        $post->categoryId = $_POST["category_id"];
        $post->user_id = $_POST["donee_id"];
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