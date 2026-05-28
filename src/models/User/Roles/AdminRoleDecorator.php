<?php
require_once __DIR__ . "/UserRoleDecorator.php";

class AdminRoleDecorator extends UserRoleDecorator{
	public function canApproveVerification(){ return true; }
}

