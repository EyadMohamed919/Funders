<?php
require_once __DIR__ . "/IUserRole.php";

class UserRoleDecorator implements IUserRole{
	protected $userRole;

	public function __construct(IUserRole $userRole)
	{
		$this->userRole = $userRole;
	}

	public function canDonate(){ return $this->userRole->canDonate(); }
	public function canCreatePost(){ return $this->userRole->canCreatePost(); }
	public function canApproveVerification(){ return $this->userRole->canApproveVerification(); }
}

