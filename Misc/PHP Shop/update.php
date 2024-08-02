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
	{
		$db->close();
		header("Location: " . getSetting("baseurl") . "error.php?error=You have to login to view your account information.");
		exit();
	}

	if(isset($_REQUEST["update"])==true)
	{
		$cust->setName($_REQUEST["name"]);
		$cust->setAddress($_REQUEST["address"]);
		$cust->setCity($_REQUEST["city"]);
		$cust->setState($_REQUEST["state"]);
		$cust->setPostCode($_REQUEST["post_code"]);
		$cust->setCountryId($_REQUEST["country"]);
		$result=$sql->getCountry($db,$cust->getCountryId());
		if($row=$result->fetchRow())
			$cust->setCountry($row["name"]);
		$cust->setCurrency($_REQUEST["currency"]);
		$sql->updateCustomer($db,$cust);
		header("Location: " . getSetting("baseurl") . "account.php");
	}

	$TPLN=new TPLN;
	$TPLN->Open("private/templates/update.tpl");

	$TPLN->Parse("head",getHead("Home"));
	$TPLN->Parse("header",getHeader());
	$TPLN->Parse("menu",getMenu($db,$sql));

	//list countrys
	$result=$sql->getCountryList($db);
	while($row=$result->fetchRow())
	{
		$TPLN->Parse("country.id",$row["id"]);
		$TPLN->Parse("country.name",$row["name"]);
		if($row["name"]==$cust->getCountry())
			$TPLN->Parse("country.selected","selected");
		else
			$TPLN->Parse("country.selected","");			
		$TPLN->Loop("country");
	}

	//list currencys
	$currency=Currency::getInstance();
	$currencies=$currency->getCurrencies();
	sort($currencies);
	foreach($currencies as $curr)
	{
		$TPLN->Parse("currency.currency",$curr);
		if($curr==$cust->getCurrency())
			$TPLN->Parse("currency.selected","selected");
		else
			$TPLN->Parse("currency.selected","");
		$TPLN->Loop("currency");
	}

	$TPLN->Parse("name",$cust->getName());
	$TPLN->Parse("address",$cust->getAddress());
	$TPLN->Parse("city",$cust->getCity());
	$TPLN->Parse("state",$cust->getState());
	$TPLN->Parse("post_code",$cust->getPostCode());

	$TPLN->Write();
	$db->close();
?>







