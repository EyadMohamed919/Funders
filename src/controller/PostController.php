<?php
require_once("../model/PostModel.php");
class PostController{
    //show list page
    public static function index(){
        $postModel = new PostModel();
        $posts = $postModel->getAllPosts();
        include("../view/posts/index.php");
    }
    //show single post
    public static function show($id){
        $postModel = new PostModel();
        $post = $postModel->getPostById($id);
        if (!$post) {
            echo "Post not found.";
            return;
        }
        else        {
            include("../view/post/show.php");
        }
    }
    //show create form
    public static function store(){
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if (empty($title) || empty($content)) {
            echo "Title and content cannot be empty.";
            return;
        }

        $postModel = new PostModel();
        $postModel->setTitle($title);
        $postModel->setDescription($content);
        $postModel->setTargetAmount(0);
        $postModel->setCurrentAmount(0);
        $postModel->setStatus('draft');
        $postModel->setImagePath('');
        $postModel->setCategoryId(0);

        $postModel->createPost();
        header("Location: /src/router/PostRouter.php");
        exit();
    }
    //update post
    public static function update($id){
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if (empty($title) || empty($content)) {
            echo "Title and content cannot be empty.";
            return;
        }

        $postModel = new PostModel();
        $existing = $postModel->getPostById($id);
        if (!$existing) {
            echo "Post not found.";
            return;
        }

        $postModel->setPost(
            $id,
            $title,
            $content,
            $existing->getTargetAmount(),
            $existing->getCurrentAmount(),
            $existing->getStatus(),
            $existing->getImagePath(),
            $existing->getCategoryId()
        );
        $postModel->updatePost();

        header("Location: /src/router/PostRouter.php");
        exit();
    }
    //delete post
    public static function delete($id){
        $postModel = new PostModel();
        $postModel->setId($id);
        $postModel->deletePost();
        header("Location: /src/router/PostRouter.php");
        exit();
    }
}

?>