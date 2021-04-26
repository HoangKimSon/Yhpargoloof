<?php

require_once BASE_PROJECT . "controller/LinkController.php";

// bath: remove unuse links
function removeUnuseLink()
{
	$controller = new LinkController();
	$controller->deleteUnuseLink();
	echo ("Remove all unuse link finish");
}
