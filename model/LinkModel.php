<?php

/**
 * LinkModel.php
 * 
 * Access database and return result. 
 */

require_once BASE_PROJECT . "library/PDODriver.php";

class LinkModel extends PDODriver
{
	function __construct()
	{
		parent::__construct();
	}

	function getAllLink($start = 1, $limit = 100)
	{
		return $this->getAllData('link', $start, $limit);
	}
}
