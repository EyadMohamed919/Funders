<?php
require_once __DIR__ . "/../view/posts/IPost.php";
abstract class PostDecorator implements IPost {
    protected IPost $decoratedPost ;
    public function __construct(IPost $Post) {
        $this->decoratedPost = $Post;
    }
    public function displayPost()
    {
        return $this->decoratedPost->displayPost();
    }
}