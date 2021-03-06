<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style type="text/css" media="screen">
		.text-center {
			text-align: center;
		}
		.text-left {
			text-align: left;
		}
		.text-red {
			color: #ff0000;
		}
		.pagination li {
			text-decoration: none;
			display: inline-block;
			margin: 0px 10px;
		}
		.link-none-style {
			text-decoration: none;
			color: #000000;
		}
		.link-none-style:hover {
			cursor: unset;
		}
	</style>
</head>

<body>

	<?php
	error_reporting(E_ALL ^ E_NOTICE);
	require_once "constant.php";
	require_once "./library/util.php";
	require_once 'router.php';
	?>
</body>

</html>