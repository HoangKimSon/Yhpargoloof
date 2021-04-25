<?php

/**
 * UserMode.php
 * 
 * Access database and return result. 
 */

require_once BASE_PROJECT . "library/PDODriver.php";
class UserModel extends PDODriver
{
	function __construct()
	{
		parent::__construct();
	}

	function register($user)
	{
		return $this->insert($user, 'user');
	}

	function getUserByName($userName)
	{
		return $this->getDataByName('user', 'username', $userName);
	}
}
