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
	require_once("private/CartItem.php");
	require_once("private/Currency.php");

	session_start();

	$db=new Database;
	$sql=new ShopSQL;

	$db->connect();

	login($db,$sql);
	$cust=$_SESSION["customer"];
	$currency=$_SESSION["currency"];

	$cart=$_SESSION["cart"];

	if(isset($_REQUEST["add"]))
	{
		$result=$sql->getItemById($db,$_REQUEST["pid"]);
		if($row=$result->fetchRow())
		{
			$item=new CartItem;
			$item->setName($row["name"]);
			$item->setReleaseDate($row["release_date"]);
			$item->setPrice($row["price"]);
			$item->setId($row["id"]);
			$item->setQuantity(1);
			$cart->addItem($item);
		}
		header("Location: " . getSetting("baseurl") . "cart.php");
	}
	else if(isset($_REQUEST["update"]))
	{
		foreach($_REQUEST as $key => $value)
		{
			$data=split("_",$key);
			if($data[0]=="quantity")		
				$cart->updateQuantity($data[1],$value);
		}
		header("Location: " . getSetting("baseurl") . "cart.php");
	}

	$TPLN=new TPLN;

	$TPLN->Open("private/templates/cart.tpl");

	$TPLN->Parse("head",getHead("Home"));
	$TPLN->Parse("header",getHeader());
	$TPLN->Parse("menu",getMenu($db,$sql));
	
	$even=false;
	$items=$cart->getItems();
	foreach($items as $item)
	{
		$TPLN->Parse("item.class",(($even=!$even)?"even":"odd"));
		$TPLN->Parse("item.name",$item->getName());
		$TPLN->Parse("item.price",$item->getPrice());
		$TPLN->Parse("item.quantity",$item->getQuantity());
		$TPLN->Parse("item.total_price",$item->getTotalPrice());
		$TPLN->Parse("item.q_id",$item->GetId());
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

	$db->close();

	$TPLN->Write();
?>





