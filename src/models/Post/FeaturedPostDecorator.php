<?php
require_once __DIR__. "/PostDecorator.php";
class FeaturedPostDecorator extends PostDecorator{
    public function getId() { return parent::getId(); }
    public function getUserId()  { return parent::getUserId(); }
    public function getCategoryId()  { return parent::getCategoryId(); }
    public function getTitle()  { return parent::getTitle(); }
    public function isFeatured() { return parent::isFeatured(); }
    public function isUrgent()  { return parent::isUrgent(); }
    public function getDetails() {  return parent::getDetails(); }
    public function getTargetAmount() {  return parent::getTargetAmount(); }

    public function displayPost(){
        return $this->highlightPost(). $this->decoratedPost->displayPost();
    }
    public function highlightPost() {
        return "<span class='badge badge-success'>Featured</span> ";
    }
}