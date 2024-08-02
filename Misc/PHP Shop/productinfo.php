<?php
/*
 * Created on 2005-mar-20 by viktor
 * 
 * Version: 1.0
 * Changes:
 */
 
	require_once("private/functions.php");
	require_once("private/database.php");
	require_once("private/shopsql.php");
	require_once("private/Currency.php");

	session_start();

	$db=new Database;
	$sql=new ShopSQL;

	$db->connect();

	login($db,$sql);
	$cust=$_SESSION["customer"];
	$currency=$_SESSION["currency"];

	$TPLN=new TPLN;
	
	$TPLN->Open("private/templates/productinfo.tpl");

	$TPLN->Parse("head",getHead("Product Information"));
	$TPLN->Parse("header",getHeader());
	$TPLN->Parse("menu",$TPLN->Parse("menu",getMenu($db,$sql)));

	//Show information about the selected product
	$result=$sql->getItemById($db,$_REQUEST["pid"]);	
	if($row=$result->fetchRow())
	{
		$TPLN->Parse("picture",getSetting("baseurl") . $row["picture"]);
		$TPLN->Parse("price",$row["price"] . " " . getSetting("currency"));
		$TPLN->Parse("currency",$currency->getPrice($cust->getCurrency(),$row["price"]) . " " . $cust->getCurrency());
		$TPLN->Parse("rrp",$row["rrp"] . " " . getSetting("currency"));
		$TPLN->Parse("region",$row["region"]);
		$TPLN->Parse("product_code",$row["id"]);
		$TPLN->Parse("release_date",substr($row["release_date"],0,10));
		$TPLN->Parse("format",$row["product"]);
		$TPLN->Parse("description",$row["description"]);
		$TPLN->Parse("cart_pid",$row["id"]);
		$TPLN->Parse("wishlist_pid",$row["id"]);
		$TPLN->Parse("name",$row["name"]);
	}

	$db->close();
	$TPLN->Write();
?>
