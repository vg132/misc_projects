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
	require_once("private/Customer.php");
	require_once("private/Currency.php");
	
	session_start();

	$db=new Database;
	$sql=new ShopSQL;

	$db->connect();

	/**
	 * Enter here is the user has posted the registratin information
	 */
	if(isset($_REQUEST["register"])==true)
	{
		if(!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$", $_REQUEST["email"]))
   		$error="Invalid email address";
   	else if($sql->customerExist($db,$_REQUEST["email"]))
   		$error="User with that email address already exist.";
   	else if(strlen($_REQUEST["password"])<6)
   		$error="Password needs to be atleast 6 characters";
   	if(!isset($error))
   	{
   		$cust=new Customer;
   		$cust->setId($db->getNextID(getSetting("seq_customer")));
   		$cust->setEmail($_REQUEST["email"]);
   		$cust->setPassword(md5($_REQUEST["password"]));
   		$cust->setName($_REQUEST["name"]);
   		$cust->setAddress($_REQUEST["address"]);
   		$cust->setCity($_REQUEST["city"]);
   		$cust->setState($_REQUEST["state"]);
   		$cust->setPostCode($_REQUEST["post_code"]);
   		$cust->setCountry($_REQUEST["country"]);
   		$cust->setCurrency($_REQUEST["currency"]);
   		$cust->setType(1);

   		if($sql->addCustomer($db,$cust)==1)
   		{
	   		$TPLN=new TPLN;
	   		$TPLN->Open("private/templates/mail/register.tpl");
	   		$TPLN->Parse("name",getSetting("pagename"));
	   		$TPLN->Parse("url",getSetting("baseurl"));
	   		mail($cust->getEmail(),"Welcome to " . getSetting("pagename"),$TPLN->Output());
	   		header("Location: " . getSetting("baseurl") . "index.php");
	    	exit();
   		}
			else
			{
				$error="Database problems, please try again later.";
			}   		
   	}
	}
	
	//Parse the registration page and put up header, menu and other stuff
	$TPLN=new TPLN;
	$TPLN->Open("private/templates/register.tpl");

	$TPLN->Parse("head",getHead("Signin"));
	$TPLN->Parse("header",getHeader());
	$TPLN->Parse("menu",$TPLN->Parse("menu",getMenu($db,$sql)));

	if(isset($error))
		$TPLN->Parse("error.error_message",$error);
	else
		$TPLN->EraseBloc("error");
	
	$result=$sql->getCountryList($db);
	while($row=$result->fetchRow())
	{
		$TPLN->Parse("country.id",$row["id"]);
		$TPLN->Parse("country.name",$row["name"]);
		if((isset($_REQUEST["country"]))&&($_REQUEST["country"]==$row["id"]))
			$TPLN->Parse("country.selected","selected");
		else
			$TPLN->Parse("country.selected","");
		$TPLN->Loop("country");
	}

	$currency=Currency::getInstance();
	$currencies=$currency->getCurrencies();

	foreach($currencies as $curr)
	{
		$TPLN->Parse("currency.currency",$curr);
		if((isset($_REQUEST["currency"]))&&($_REQUEST["currency"]==$curr["currency"]))
			$TPLN->Parse("currency.selected","selected");
		else
			$TPLN->Parse("currency.selected","");
		$TPLN->Loop("currency");
	}

	//If there was somthing wrong with the information enterd put it back, if
	//its the first visit show empty text boxes.
	$TPLN->Parse("email",(isset($_REQUEST["email"])?$_REQUEST["email"]:""));
	$TPLN->Parse("name",(isset($_REQUEST["name"])?$_REQUEST["name"]:""));
	$TPLN->Parse("address",(isset($_REQUEST["address"])?$_REQUEST["address"]:""));
	$TPLN->Parse("city",(isset($_REQUEST["city"])?$_REQUEST["city"]:""));
	$TPLN->Parse("state",(isset($_REQUEST["state"])?$_REQUEST["state"]:""));
	$TPLN->Parse("post_code",(isset($_REQUEST["post_code"])?$_REQUEST["post_code"]:""));

	$db->close();
	
	$TPLN->Write();
?>





