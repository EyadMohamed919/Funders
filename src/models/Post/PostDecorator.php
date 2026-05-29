<?php
require_once __DIR__ . "/IPost.php";
abstract class PostDecorator implements IPost {
    protected IPost $decoratedPost ;
    public function getId() { return null; }
    public function getUserId()  { return null; }
    public function getCategoryId()  { return null; }
    public function getTitle()  { return null; }
    public function isFeatured() { return null; }
    public function isUrgent()  { return null; }
    public function getDetails() {  null; }
    public function __construct(IPost $Post) {
        $this->decoratedPost = $Post;
    }
    public function displayPost()
    {
        return $this->decoratedPost->displayPost();
    }
}