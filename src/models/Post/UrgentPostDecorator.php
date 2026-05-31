<?php
require_once __DIR__. "/PostDecorator.php";
class UrgentPostDecorator extends PostDecorator
{
    public function getId() { return parent::getId(); }
    public function getUserId()  { return parent::getUserId(); }
    public function getCategoryId()  { return parent::getCategoryId(); }
    public function getTitle()  { return parent::getTitle(); }
    public function isFeatured() { return parent::isFeatured(); }
    public function isUrgent()  { return parent::isUrgent(); }
    public function getDetails() {  return parent::getDetails(); }
    public function getTargetAmount() {  return parent::getTargetAmount(); }

    public function displayPost() {
        return $this->addUrgentBadge(). $this->decoratedPost->displayPost();
    }  
    public function addUrgentBadge() {
        return "<span class='badge badge-danger'>Urgent</span> ";
    }
}