<?php
require_once __DIR__ . "/IPost.php";
abstract class PostDecorator implements IPost {
    protected IPost $decoratedPost ;
    public function getId() { return $this->decoratedPost->getId(); }
    public function getUserId()  { return $this->decoratedPost->getUserId(); }
    public function getCategoryId()  { return $this->decoratedPost->getCategoryId(); }
    public function getTitle()  { return $this->decoratedPost->getTitle(); }
    public function isFeatured() { return $this->decoratedPost->isFeatured(); }
    public function isUrgent()  { $this->decoratedPost->isUrgent(); }
    public function getDetails() {  return $this->decoratedPost->getDetails(); }
    public function getTargetAmount() {  return $this->decoratedPost->getTargetAmount(); }
    public function __construct(IPost $Post) {
        $this->decoratedPost = $Post;
    }
    public function displayPost()
    {
        return $this->decoratedPost->displayPost();
    }
}