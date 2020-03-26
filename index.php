<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

session_start();

include_once "include/paths.php";
include_once "include/fatal.php";
include_once "include/db/conn.php";
include_once "include/routes.php";
include_once "include/bin.php";

$_SESSION["flash_set"] = false;

if (!isset($_SESSION["flash"]))
    $_SESSION["flash"] = array();

handle_route();

if (!$_SESSION["flash_set"])
    unset($_SESSION["flash"]);
else
    $_SESSION["flash_set"] = false;