<?php

/**
 * UserController.php
 * 
 * User controller. Catch query param m in url to define  used method
 */

require_once BASE_PROJECT . "model/UserModel.php";

class UserController
{
	private $__userModel;

	function __construct()
	{
		$this->__userModel = new UserModel();
	}
	/**
	 * Show reigster form
	 *
	 */
	function index()
	{
		$this->register();
	}

	/**
	 * Show register form
	 *
	 */
	function register()
	{
		require BASE_PROJECT . "view/user/register.html";
	}

	function doRegister()
	{
		// validate
		if ($_SERVER["REQUEST_METHOD"] != "POST") {
			return header("Location:index.php");
		}

		$username = isset($_POST['username']) ? trim($_POST['username']) : "";
		$password = isset($_POST['password']) ? trim($_POST['password']) : "";
		if (!$username || !$password) {
			return header("Location:index.php?c=user&m=register&mess=m");
		}

		if ($this->__userModel->getUserByName($username)) { // check if user is exist
			return header("Location:index.php?c=user&m=register&mess=d");
		}

		$newUser = [
			'username' => $username,
			'password' => md5($password)
		];
		$this->__userModel->register($newUser);
		return header("Location:index.php?c=user&m=login");
	}

	function login(){
		echo("register succesful");
	}
}

$obj = new UserController();
$con = isset($_GET['m']) ? trim($_GET['m']) : 'index';
$obj->$con();
