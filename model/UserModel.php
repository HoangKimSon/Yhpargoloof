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

	function login($user)
	{
		$authenUser = [];
		$sql  = "SELECT * FROM `user` WHERE `username` = :username AND `password` = :pass";

		$stmt = $this->conn->prepare($sql);
		if ($stmt) {
			$stmt->bindParam(":username", $user['username'], PDO::PARAM_STR);
			$stmt->bindParam(":pass", $user['password'], PDO::PARAM_STR);
			if ($stmt->execute()) {
				$authenUser = $stmt->fetch(PDO::FETCH_ASSOC);
			} else {
				print_r($stmt->errorInfo());
			}
			$stmt->closeCursor();
		}
		return $authenUser;
	}

	function getUserByName($userName)
	{
		return $this->getDataByName('user', 'username', $userName);
	}
}
