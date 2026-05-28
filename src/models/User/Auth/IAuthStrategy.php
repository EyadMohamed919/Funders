<?php
interface IAuthStrategy{
	public function authenticate($contactValue, $password);
}

