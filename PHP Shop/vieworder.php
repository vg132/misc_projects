<?php
	/**
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

	$cart=$_SESSION["cart"];
	$cust=$_SESSION["customer"];
	//check if the customer is logged in, guests have type 0.
	if($cust->getType()<=0)
	{
		header("Location: " . getSetting("baseurl") . "error.php?error=You have to login to checkout your cart.");
		exit();		
	}

	$TPLN=new TPLN;
	
	$TPLN->Open("private/templates/vieworder.tpl");
	
	$TPLN->Parse("head",getHead("Home"));
	$TPLN->Parse("header",getHeader());
	$TPLN->Parse("menu",getMenu($db,$sql));

	//list all items in the selected order and the total price for the order
	$totalPrice=0;
	$even=false;
	$result=$sql->getInvoiceItems($db,$_REQUEST["orderid"]);
	while($row=$result->fetchRow())
	{
		$TPLN->Parse("item.class",(($even=!$even)?"even":"odd"));
		$TPLN->Parse("item.name",$row["name"]);
		$TPLN->Parse("item.price",$row["price"]);
		$TPLN->Parse("item.quantity",$row["quantity"]);
		$TPLN->Parse("item.total_price",($row["price"]*$row["quantity"]));
		$totalPrice+=($row["price"]*$row["quantity"]);
		$TPLN->Loop("item");
	}
	if($result->numRows()<=0)
		$TPLN->Parse("error.message","No items found in this order.");
	else
		$TPLN->EraseBloc("error");

	$TPLN->Parse("total.total_price",getSetting("currency") . " " . $totalPrice);
	$TPLN->Parse("total.class",(($even=!$even)?"even":"odd"));

	$TPLN->Parse("currency.class",(($even=!$even)?"even":"odd"));
	$TPLN->Parse("currency.currency_price",getSetting("currency") . " " . $totalPrice);

	$db->close();
	
	$TPLN->Write();
?>