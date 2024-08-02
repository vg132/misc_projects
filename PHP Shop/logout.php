<?php
/*
 * Created on 2005-mar-22 by viktor
 * 
 * Version: 1.0
 * Changes:
 */
	require_once("private/functions.php");

	session_start();
	
	//Reset session and remove all cookies set by this page.
	$_SESSION["customer"]=null;
	$_SESSION["cart"]=null;

	removeCookie();

	header("Location: " . getSetting("baseurl") . "index.php");
	exit();
?>
