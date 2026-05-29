<?php
require_once __DIR__ . "/../models/Post/PostModel.php";
require_once __DIR__ . "/../models/Post/UrgentPostDecorator.php";
require_once __DIR__ . "/../models/Post/FeaturedPostDecorator.php";

class PostController {

    public function show(int $id) {
        $postModel = new PostModel();
        $post = $postModel->getPostById($id);

        if (!$post) {
            die("Post not found.");
        }

        $post = $this->applyDecorators($post);
        return $post;
    }

    public function showAllPosts() {
        $postModel = new PostModel();
        $rawPosts = $postModel->getAllPosts();
        $posts = [];
        foreach ($rawPosts as $post) {
            $posts[] = $this->applyDecorators($post);
        }

        return $posts;
    }

    public function admin(): void {
        $postModel = new PostModel();
        $posts = $postModel->getAllPosts();  // raw PostModel objects
        include __DIR__ . "/../view/posts/post_table.php";
    }

    private function applyDecorators(PostModel $post): IPost {
    
        if ($post->featured) {
            $post = new FeaturedPostDecorator($post);
        }else if ($post->urgent) {
            $post = new UrgentPostDecorator($post);
        }
        return $post;
    }
}