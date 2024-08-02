<?php
/*
 * Created on 2005-mar-21 by viktor
 * 
 * Version: 1.0
 * Changes:
 */
 
	require_once("private/functions.php");
	require_once("private/database.php");
	require_once("private/shopsql.php");

	session_start();

	$db=new Database;
	$sql=new ShopSQL;

	$db->connect();

	login($db,$sql);

	$TPLN=new TPLN;
	$TPLN_tmp=new TPLN;

	$TPLN->Open("private/templates/productlist.tpl");

	$TPLN->Parse("head",getHead("Products"));
	$TPLN->Parse("header",getHeader());
	$TPLN->Parse("menu",$TPLN->Parse("menu",getMenu($db,$sql)));

	//list all products in the selected group or category.
	if(isset($_REQUEST["cid"]))
		$result=$sql->getItemByProductGroupAndCategory($db,$_REQUEST["fid"],$_REQUEST["cid"]);
	else
		$result=$sql->getItemByProductGroupId($db,$_REQUEST["fid"]);
	while($row=$result->fetchRow())
	{
		$TPLN->Parse("product.name",$row["name"]);
		$TPLN->Parse("product.id",$row["id"]);
		$TPLN->Parse("product.price",$row["price"] . " " . getSetting("currency"));
		$TPLN->Parse("product.release_date",substr($row["release_date"],0,10));
		$TPLN->Parse("product.list.wishlist_pid",$row["id"]);
		$TPLN->Parse("product.cart_pid",$row["id"]);
		$TPLN->Loop("product");
	}
	$TPLN->EraseBloc("wishlist");
	if($result->numRows()<=0)
	{
		$TPLN->Parse("error.message","No products found.");
		$TPLN->EraseBloc("product");
	}
	else
	{
		$TPLN->EraseBloc("error");
	}

	$db->close();
	$TPLN->Write();
?>
