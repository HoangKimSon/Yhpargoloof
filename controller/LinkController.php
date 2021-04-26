<?php

/**
 * LinkController.php
 * 
 * Link controller. Catch query param m in url to define used method
 */

require_once BASE_PROJECT . "model/LinkModel.php";
require_once BASE_PROJECT . "model/UserLinkModel.php";
require_once BASE_PROJECT . "library/Paging.php";

class LinkController
{
	private $__linkModel;
	private $__userLinkModel;

	function __construct()
	{
		$this->__linkModel = new LinkModel();
		$this->__userLinkModel = new UserLinkModel();
	}

	// Get all links in database
	function index()
	{
		if (isset($_GET['link'])) {
			$newLink = $this->__linkModel->getLinkById($_GET['link']); // use in html file
		}

		// paging
		$pagination = new Paging();
		$totalRecord = $this->__linkModel->countAllLink();
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$link  = $pagination->create_links('', array('page' => $page));
		$pagView = $pagination->pagenations($link, $totalRecord, $page, ROW_LIMIT);

		// list link on view
		$listLink = $this->__linkModel->getAllLink($pagView['start'], $pagView['limit']); //use in html file
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

			// associated user id and link id
			if ($_SESSION['userId']) {
				$userLink = [
					'user_id' => $_SESSION['userId'],
					'link_id' => $newLink
				];
				$this->__userLinkModel->insertUserLink($userLink);
			}

			return header("Location:index.php?link={$newLink}");
		} else {
			return header("Location:index.php");
		}
	}

	 // show edit form 
	function edit()
	{
		$id = isset($_GET['id']) ? trim($_GET['id']) : "";
		if (!$id) {
			return header("Location:index.php?c=user&m=userLink");
		}

		$link = $this->__linkModel->getLinkById($id); // use in html
		require_once "view/link/edit.html";
	}

	 // edit link, get data from $_POST
	function doEdit()
	{
		if ($_SERVER["REQUEST_METHOD"] != "POST") {
			return header("Location:index.php?c=user&m=userLink");
		}

		$originLink = isset($_POST['link']) ? trim($_POST['link']) : "";
		$id = isset($_POST['id']) ? trim($_POST['id']) : "";
		if (!$originLink || !$id) {
			return header("Location:index.php?m=edit&id={$id}&mess=e");
		}

		$data = [
			'origin_link' => $originLink,
			'id' => $id
		];

		$this->__linkModel->updateLink($data);

		$newLink = $this->__linkModel->getLinkById($id); // use in html file
		return header("Location:index.php?c=user&m=userLink");
	}

	 // delete link, get data from $_GET
	function doDelete()
	{
		$this->__linkModel->deleteLink($_GET['id']); // remove link
		$this->__userLinkModel->deleteUserLink($_SESSION['userId'], $_GET['id']); // remove associated link id with user id

		return header("Location:index.php?c=user&m=userLink");
	}


	// increase number click to link, get data from $_POST
	function doCount()
	{
		// validate
		$linkId = isset($_GET['id']) ? $_GET['id'] : 0;

		$currentLink = $this->__linkModel->getLinkById($linkId);
		// dd($currentLink);
		if (!$currentLink) {
			return;
		}

		$newLink = [
			'visit_time' => $currentLink['visit_time'] + 1,
			'id' => $currentLink['id']
		];
		return $this->__linkModel->updateLink($newLink);
	}

	 // delete unuse link
	function deleteUnuseLink()
	{
		$linkIds = [];

		$associatedLink = $this->__userLinkModel->getDistinctField('link_id'); // all associated links with users

		foreach ($associatedLink as $key => $value) {
			array_push($linkIds, $value['link_id']); // get all link Id to array
		}
		$conditionRaw = "(" . implode(",", $linkIds) . ")"; // generate sql condition

		$this->__linkModel->removeUnuseLink($conditionRaw);
	}
}
$obj = new LinkController();
$method = isset($_GET['m']) ? trim($_GET['m']) : 'index';
$obj->$method();
