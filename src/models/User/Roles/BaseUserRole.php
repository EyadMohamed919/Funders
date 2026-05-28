<?php
require_once __DIR__ . "/IUserRole.php";

class BaseUserRole implements IUserRole{
	public function canDonate(){ return false; }
	public function canCreatePost(){ return false; }
	public function canApproveVerification(){ return false; }
}

