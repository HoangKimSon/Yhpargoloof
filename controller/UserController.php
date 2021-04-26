<?php

/**
 * UserController.php
 * 
 * User controller. Catch query param m in url to define  used method
 */

require_once BASE_PROJECT . "model/UserModel.php";
require_once BASE_PROJECT . "model/UserLinkModel.php";

class UserController
{
	private $__userModel;
	private $__userLinkModel;

	function __construct()
	{
		$this->__userModel = new UserModel();
		$this->__userLinkModel = new UserLinkModel();
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
		if (isset($_SESSION['username'])) {
			return header("Location:index.php");
		}

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

	// show login form
	function login()
	{
		if (isset($_SESSION['username'])) {
			return header("Location:index.php");
		}
		require BASE_PROJECT . "view/user/login.html";
	}

	// login user, get data from $_POST
	function doLogin()
	{
		if ($_SERVER["REQUEST_METHOD"] != "POST") {
			return header("Location:index.php");
		}

		// validate
		$username = isset($_POST['username']) ? trim($_POST['username']) : "";
		$password = isset($_POST['password']) ? trim($_POST['password']) : "";
		if (!$username || !$password) {
			return header("Location:index.php?c=user&m=login&mess=m");
		}

		$user = [
			'username' => $username,
			'password' => md5($password)
		];
		$authenUser = $this->__userModel->login($user);
		if ($authenUser) { // login succesful
			$_SESSION["username"] = $authenUser["username"];
			$_SESSION["userId"] = $authenUser["id"];
			return header("Location:index.php");
		} else {
			return header("Location:index.php?c=user&m=login&mess=nf");
		}
	}

	 // show all links of specific user
	function userLink()
	{
		if (!$_SESSION["username"]) {
			return header("Location:index.php?c=user&m=login");
		}

		// use in html file
		$linkList = $this->__userLinkModel->getUserLink($_SESSION["userId"]);
		require BASE_PROJECT . "view/user/userLink.html";
	}
}

$obj = new UserController();
$con = isset($_GET['m']) ? trim($_GET['m']) : 'index';
$obj->$con();
