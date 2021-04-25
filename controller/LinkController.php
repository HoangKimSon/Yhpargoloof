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

	// Get all links in database
	function index()
	{
		if (isset($_GET['link'])) {
			$newLink = $this->__linkModel->getLinkById($_GET['link']); // use in html file
		}

		// list link on view
		$listLink = $this->__linkModel->getAllLink(); //use in html file
		// dd($listLink);
		require_once "./view/link/view.html";
	}

	// create new link, get data from $_POST
	function doCreate()
	{
		if ($_SERVER["REQUEST_METHOD"] != "POST") {
			return header("Location:index.php");
		}

		// validate
		if (isset($_POST['link'])) {
			$originLink = trim($_POST['link']);
		} else {
			return header("Location:index.php?code=400");
		}

		// check if link is exist
		$oldLink = $this->__linkModel->geLinkByName($originLink);
		if ($oldLink) {
			return header("Location:index.php?mess=d");
		}

		// shorten link
		$shortenLink = base_convert(time(), 10, 32);;

		$data = [
			'origin_link' => $originLink,
			'shorten_link' => $shortenLink
		];
		if ($this->__linkModel->insertLink($data)) { // insert link successful
			$newLink = $this->__linkModel->getLastInsertedLink()['id'];

			return header("Location:index.php?link={$newLink}");
		} else {
			return header("Location:index.php");
		}
	}
}
$obj = new LinkController();
$method = isset($_GET['m']) ? trim($_GET['m']) : 'index';
$obj->$method();
