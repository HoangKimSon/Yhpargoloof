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

	function getUserLink($userId)
	{
		$sql  = "SELECT * FROM user JOIN user_link on user.id = user_link.user_id JOIN link ON link.id = user_link.link_id WHERE user.id = :{$userId}";
		$stmt = $this->conn->prepare($sql);
		if ($stmt) {
			$stmt->bindParam(":{$userId}", $userId, PDO::PARAM_STR);
			if ($stmt->execute()) {
				$this->__data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			$stmt->closeCursor();
		}
		return $this->__data;
	}

	function insertUserLink($userLink)
	{
		return $this->insert($userLink, 'user_link');
	}
}
