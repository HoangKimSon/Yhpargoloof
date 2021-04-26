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

	function geLinkByName($originLink)
	{
		return $this->getDataByName('link', 'origin_link', $originLink);
	}

	function getLinkById($id)
	{
		return $this->getDataById('link', $id);
	}

	function updateLink($link)
	{
		return $this->update('link', $link, $link['id']);
	}

	function getLastInsertedLink()
	{
		return $this->getLastInsertedData('link');
	}

	function insertLink($data)
	{
		return $this->insert($data, 'link');
	}

	function deleteLink($id)
	{
		return $this->delete('link', $id);
	}

	function removeUnuseLink($associatedLink)
	{
		$sql  = "DELETE FROM `link` WHERE `id` NOT IN {$associatedLink} AND DATEDIFF(CURRENT_DATE, updated_at) > 30";
		$stmt = $this->conn->prepare($sql);
		if ($stmt) {
			if ($stmt->execute()) {
				$this->__flag = TRUE;
			}
			$stmt->closeCursor();
		}
		return $this->__flag;
	}

	function countAllLink()
	{
		return $this->countAllData('link');
	}
}
