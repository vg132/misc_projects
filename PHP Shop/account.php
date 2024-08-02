<?php
	require_once("private/functions.php");
	require_once("private/database.php");
	require_once("private/shopsql.php");
	require_once("private/Invoice.php");
	require_once("private/InvoiceItem.php");
	require_once("private/Currency.php");

	session_start();

	$db=new Database;
	$sql=new ShopSQL;

	$db->connect();

	login($db,$sql);

	$cust=$_SESSION["customer"];

	//Check if user is logged in, if not then redirect to error page
	if($cust->getType()<=0)
	{		$db->close();
		header("Location: " . getSetting("baseurl") . "error.php?error=You have to login to view your account information.");
		exit();
	}

	$TPLN=new TPLN;
	$TPLN->Open("private/templates/account.tpl");

	$TPLN->Parse("head",getHead("Home"));
	$TPLN->Parse("header",getHeader());
	$TPLN->Parse("menu",getMenu($db,$sql));

	$TPLN->Write();
	$db->close();
?>







