<?php
require_once __DIR__ . "/../model/PostModel.php";
require_once __DIR__ . "/../model/decorators/UrgentPostDecorator.php";
require_once __DIR__ . "/../model/decorators/FeaturedPostDecorator.php";

class PostController {

    public function show(int $id): void {
        $postModel = new PostModel();
        $post = $postModel->getPostById($id);

        if (!$post) {
            die("Post not found.");
        }

        $post = $this->applyDecorators($post);
        include __DIR__ . "/../view/posts/single_post.php";
    }

    public function index(): void {
        $postModel = new PostModel();
        $rawPosts = $postModel->getAllPosts();
        $posts = [];
        foreach ($rawPosts as $post) {
            $posts[] = $this->applyDecorators($post);
        }

        include __DIR__ . "/../view/posts/post_list.php";
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