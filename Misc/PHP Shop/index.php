<?php
/*
 * Created on 2005-mar-19 by viktor
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
	
	$TPLN->Open("private/templates/index.tpl");
	
	$TPLN->Parse("head",getHead("Home"));
	$TPLN->Parse("header",getHeader());
	$TPLN->Parse("menu",getMenu($db,$sql));

	//Get all product groups and 4 items from every group.
	$pg=$sql->getProductGroups($db);
	while($product=$pg->fetchRow())
	{
		$TPLN->Parse("product_group.name",$product["name"]);
		$TPLN->Parse("product_group.pgid",$product["category_group_id"]);
		$TPLN->Parse("product_group.fid",$product["id"]);
		$top=0;
		$items=$sql->getNewestItemByProductGroup($db,$product["id"]);
		while(($item=$items->fetchRow())&&($top<4))
		{
			$TPLN->Parse("product_group.product.picture",getSetting("baseurl") . $item["small_picture"]);
			$TPLN->Parse("product_group.product.id",$item["id"]);
			$TPLN->Parse("product_group.product.name",$item["name"]);
			$TPLN->Parse("product_group.product.name2",$item["name"]);
			$TPLN->Parse("product_group.product.price",$item["price"] . " " . getSetting("currency"));
			$TPLN->Loop("product_group.product");
			$top++;
		}
		$TPLN->Loop("product_group");
	}

	$db->close();
	
	$TPLN->Write();
?>