<?php

/**
 * UserLinkModel.php
 * 
 * Access database and return result. 
 */

require_once BASE_PROJECT . "library/PDODriver.php";

class UserLinkModel extends PDODriver
{
	function __construct()
	{
		parent::__construct();
	}

	function insertUserLink($userLink)
	{
		return $this->insert($userLink, 'user_link');
	}
}
