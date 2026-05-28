<?php
interface IUserRole{
	public function canDonate();
	public function canCreatePost();
	public function canApproveVerification();
}

