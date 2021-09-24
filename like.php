<?php

require_once("db_functions.php");
require_once("session_verify.php");
session_start();

if (!(verify_session($_SESSION["login"], $_SESSION["passwd"])))
{
	$_SESSION["login"] = "";
	header("Location: index.php");
}

if (isset($_POST['image_id']) && $_POST['image_id'] != "")
	like($_SESSION['login'], $_POST['image_id']);

?>