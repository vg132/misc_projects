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
	require_once("private/Currency.php");

	session_start();

	$db=new Database;
	$sql=new ShopSQL;

	$db->connect();

	login($db,$sql);

	$cart=$_SESSION["cart"];
	$cust=$_SESSION["customer"];
	$currency=$_SESSION["currency"];

	if($cust->getType()<=0)
	{
		header("Location: " . getSetting("baseurl") . "error.php?error=You have to login to checkout your cart.");
		exit();		
	}

	$TPLN=new TPLN;
	
	$TPLN->Open("private/templates/checkout.tpl");
	
	$TPLN->Parse("head",getHead("Home"));
	$TPLN->Parse("header",getHeader());
	$TPLN->Parse("menu",getMenu($db,$sql));

	//list all items and the total price
	$even=false;
	$items=$cart->getItems();
	foreach($items as $item)
	{
		$TPLN->Parse("item.class",(($even=!$even)?"even":"odd"));
		$TPLN->Parse("item.name",$item->getName());
		$TPLN->Parse("item.price",$item->getPrice());
		$TPLN->Parse("item.quantity",$item->getQuantity());
		$TPLN->Parse("item.total_price",$item->getTotalPrice());
		$TPLN->Loop("item");
	}
	if(count($items)<=0)
	{
		$TPLN->EraseBloc("item");
	}

	$TPLN->Parse("total.total_price",$cart->getTotalPrice() . " " . getSetting("currency"));
	$TPLN->Parse("total.class",(($even=!$even)?"even":"odd"));

	$TPLN->Parse("currency.class",(($even=!$even)?"even":"odd"));
	$TPLN->Parse("currency.currency_price",$currency->getPrice($cust->getCurrency(),$cart->getTotalPrice()) . " " . $cust->getCurrency());


	$TPLN->Parse("name",$cust->getName());
	$TPLN->Parse("address",$cust->getAddress());
	$TPLN->Parse("city",$cust->getCity());
	$TPLN->Parse("post_code",$cust->getPostCode());
	$TPLN->Parse("state",$cust->getState());
	$TPLN->Parse("country",$cust->getCountry());

	$db->close();
	
	$TPLN->Write();
?>
