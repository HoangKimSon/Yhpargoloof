<?php

/**
 * Routes.php
 * 
 * App routes, all router url go through this class. Catch query param c in url to define controller
 */
class Router
{
  function index()
  {
    require_once "./controller/LinkController.php";
  }
}
$obj = new Router();
$con = isset($_GET['c']) ? trim($_GET['c']) : 'index';
$obj->$con();
