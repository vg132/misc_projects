<?php
/*
 * Created on 2005-mar-23 by viktor
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

	$cust=$_SESSION["customer"];

	//Check if the user is logged on, if not dont give access to wish list
	if($cust->getType()<=0)
	{
		$error="You have to login to have a wish list.";
	}
	else if(isset($_REQUEST["add"]))
	{
		$sql->addWishlistItem($db,$cust->getId(),$_REQUEST["pid"]);
	}
	else if(isset($_REQUEST["remove"]))
	{
		$sql->removeFromWishlist($db,$_REQUEST["pid"]);
	}

	//Parse the template and present it to the user
	$TPLN=new TPLN;

	$TPLN->Open("private/templates/productlist.tpl");
	
	$TPLN->Parse("head",getHead("Wish list"));
	$TPLN->Parse("header",getHeader());
	$TPLN->Parse("menu",getMenu($db,$sql));

	if(isset($error))
	{
		$TPLN->Parse("error.message",$error);
		$TPLN->EraseBloc("product");
	}
	else
	{
		$result=$sql->getWishlist($db,$cust->getId());
		while($row=$result->fetchRow())
		{
			$TPLN->Parse("product.name",$row["name"]);
			$TPLN->Parse("product.release_date",substr($row["release_date"],0,10));
			$TPLN->Parse("product.price",$row["price"]);
			$TPLN->Parse("product.id",$row["item_id"]);
			$TPLN->Parse("product.wishlist.remove_pid",$row["id"]);
			$TPLN->Parse("product.cart_pid",$row["id"]);
			$TPLN->Loop("product");
		}
		$TPLN->EraseBloc("list");
		if($result->numRows()>0)
			$TPLN->EraseBloc("error");
		else
			$TPLN->EraseBloc("product");
	}
	
	$db->close();
	
	$TPLN->Write();
?>
















