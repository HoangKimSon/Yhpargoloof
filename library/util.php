<?php

/**
 * Supported funtions
 */

// die and dump
function dd($data, $text = "")
{
	echo "<pre>";
	if ($text) echo $text . ": ";
	var_dump($data);
	echo "</pre>";
	die();
}
