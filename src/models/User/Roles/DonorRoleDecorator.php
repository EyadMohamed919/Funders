<?php
require_once __DIR__ . "/UserRoleDecorator.php";

class DonorRoleDecorator extends UserRoleDecorator{
	public function canDonate(){ return true; }
}

