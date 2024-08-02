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

	session_start();

	$db=new Database;
	$sql=new ShopSQL;

	$db->connect();

	//if the login data was posted to this script run this part to login,
	if(isset($_REQUEST["signin"]))
	{
		$result=$sql->login($db,$_REQUEST["email"],md5($_REQUEST["password"]));
		if($row=$result->fetchRow())
		{
			$cust=new Customer;
   		$cust->setId($row["id"]);
   		$cust->setEmail($row["email"]);
   		$cust->setName($row["name"]);
   		$cust->setAddress($row["address"]);
   		$cust->setCity($row["city"]);
   		$cust->setState($row["state"]);
   		$cust->setPostCode($row["post_code"]);
   		$cust->setCountry($row["country"]);
   		$cust->setType($row["customer_type"]);
   		$cust->setCurrency($row["currency"]);
   		$_SESSION["customer"]=$cust;

			if(isset($_REQUEST["savelogin"]))
				saveCookie($db,$sql,$_REQUEST["email"]);
   		header("Location: " . getSetting("baseurl") . "index.php");
    	exit();
		}
		$error="Wrong username and/or password.";
	}

	$TPLN=new TPLN;
	
	$TPLN->Open("private/templates/signin.tpl");

	$TPLN->Parse("head",getHead("Signin"));
	$TPLN->Parse("header",getHeader());
	$TPLN->Parse("menu",$TPLN->Parse("menu",getMenu($db,$sql)));

	if(isset($error))
	{
		$TPLN->Parse("error.error_message",$error);
		$TPLN->Parse("email",$_REQUEST["email"]);
	}
	else
	{
		$TPLN->EraseBloc("error");
		$TPLN->Parse("email","");
	}
	
	$db->close();
	
	$TPLN->Write();
?>
