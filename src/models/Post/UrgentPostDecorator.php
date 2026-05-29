<?php
require_once __DIR__. "/PostDecorator.php";
class UrgentPostDecorator extends PostDecorator
{
    public function getId() { return null; }
    public function getUserId()  { return null; }
    public function getCategoryId()  { return null; }
    public function getTitle()  { return null; }
    public function isFeatured() { return null; }
    public function isUrgent()  { return null; }
    public function getDetails() {  null; }

    public function displayPost(): string {
        return $this->addUrgentBadge(). $this->decoratedPost->displayPost();
    } 
    public function addUrgentBadge(): string {
        return "<span class='badge badge-danger'>Urgent</span> ";
    }
}