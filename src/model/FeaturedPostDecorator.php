<?php
require_once __DIR__. "/PostDecorator.php";
class FeaturedPostDecorator extends PostDecorator{
    public function displayPost(): string{
        return $this->highlightPost(). $this->decoratedPost->displayPost();
    }
    public function highlightPost(): string {
        return "<span class='badge badge-success'>Featured</span> ";
    }
}