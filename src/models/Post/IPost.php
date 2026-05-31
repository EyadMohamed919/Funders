<?php
interface Ipost {
    function displayPost();

    public function getId();
    public function getUserId();
    public function getCategoryId();
    public function getTitle();
    public function isFeatured();
    public function isUrgent();
    public function getDetails();
    public function getTargetAmount();
}