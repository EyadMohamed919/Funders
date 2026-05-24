<?php
require_once __DIR__. "/PostDecorator.php";
class UrgentPostDecorator extends PostDecorator
{
    public function displayPost(): string {
        return $this->addUrgentBadge(). $this->decoratedPost->displayPost();
    } 
    public function addUrgentBadge(): string {
        return "<span class='badge badge-danger'>Urgent</span> ";
    }
}