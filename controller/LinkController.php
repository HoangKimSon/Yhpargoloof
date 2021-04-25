<?php

/**
 * LinkController.php
 * 
 * Link controller. Catch query param m in url to define used method
 */

require_once BASE_PROJECT . "model/LinkModel.php";

class LinkController
{
	private $__linkModel;
	function __construct()
	{
		$this->__linkModel = new LinkModel();
	}

	/**
	 * Get all links in database
	 *
	 */
	function index()
	{
		// list link on view
		$listLink = $this->__linkModel->getAllLink(); //use in html file
		require_once "./view/link/view.html";
	}
}
$obj = new LinkController();
$method = isset($_GET['m']) ? trim($_GET['m']) : 'index';
$obj->$method();
