<?php
require_once __DIR__ . "/UserRoleDecorator.php";

class DoneeRoleDecorator extends UserRoleDecorator{
	public function canCreatePost(){ return true; }
}

