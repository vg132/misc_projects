<?php
	/**
	 * This file contains system wide help functions to keep them centralized and easy
	 * to maintain if needed.
	 */

	require_once("TPLN/TPLN.php");
	require_once("Configuration.php");
	require_once("Customer.php");
	require_once("Cart.php");
	require_once("Currency.php");

	/**
	 * Returns the desired configuration setting.
	 * 
	 * param $name the name of the setting.
	 */
	function getSetting($name)
	{
		$config=Configuration::getInstance();
		return($config->get($name));
	}
	
	/**
	 * Creates and returns the meta header for all html documents.
	 *  
	 * param $title The title of the page.
	 */
	function getHead($title)
	{
		$TPLN=new TPLN;
		$TPLN->Open(getSetting("base_directory") . "private/templates/head.tpl");
		$TPLN->Parse("title",$title);
		$TPLN->Parse("base",getSetting("baseurl"));
		return($TPLN->Output());
	}

	/**
	 * Creates and returns the top header menu shown on all pages.
	 */
	function getHeader()
	{
		$TPLN=new TPLN;
		$TPLN->Open(getSetting("base_directory") . "private/templates/header.tpl");
		if(isset($_SESSION["customer"]))
		{
			$cust=$_SESSION["customer"];
			if($cust->getType()>0)
				$TPLN->EraseBloc("signin");
			else
				$TPLN->EraseBloc("account");
		}
		else
		{
			$TPLN->EraseBloc("account");
		}
		return($TPLN->Output());
	}

	/**
	 * Converts a system path to a URL.
	 * 
	 * param $path the system path that will be transformed to a URL
	 */
	function PathToURL($path)
	{
		$pos=strpos(getSetting("baseurl"),$_SERVER["SERVER_NAME"]);
		if(!$pos)
			return(null);
		else
			return(substr($path,strlen($_SERVER["DOCUMENT_ROOT"] . substr(getSetting("baseurl"),strpos(getSetting("baseurl"),"/",$pos)))));
	}

	/**
	 * Creates the menu seen on the left side of all pages.
	 * 
	 * param $db a open database object.
	 * param $sql a open shopsql object.
	 */
	function getMenu($db, $sql)
	{
		$TPLN=new TPLN;
		$TPLN->Open(getSetting("base_directory") . "private/templates/menu.tpl");

		$result=$sql->getProductGroups($db);
		while($row=$result->fetchRow())
		{
			$url=$_SERVER["SCRIPT_NAME"] . "?";
			foreach($_GET as $key=>$value)
			{
				if(($key!="pgid")&&($key!="cid"))
					$url=$url . $key . "=" . $value . "&";
			}
			if(strlen($_SERVER["QUERY_STRING"])>0)
				$url=$url . "pgid=" . $row["category_group_id"] . "&fid=" . $row["id"];
			else
				$url=$url . "pgid=" . $row["category_group_id"] . "&fid=" . $row["id"];

			$TPLN->Parse("product_list.url",$url);
			$TPLN->Parse("product_list.name",$row["name"]);
			
			$TPLN->Parse("product_list2.id",$row["id"]);
			$TPLN->Parse("product_list2.name",$row["name"]);
	
			$TPLN->Loop("product_list");
			$TPLN->Loop("product_list2");
		}
		if(isset($_REQUEST["pgid"]))
		{
			$TPLN->Parse("categorys.pgid",$_REQUEST["pgid"]);
			$TPLN->Parse("categorys.fid",$_REQUEST["fid"]);
			$result=$sql->getCategorysByCategoryGroupId($db,$_REQUEST["pgid"]);
			while($row=$result->fetchRow())
			{
				$TPLN->Parse("categorys.category.name",$row["name"]);
				$TPLN->Parse("categorys.category.pgid",$_REQUEST["pgid"]);
				$TPLN->Parse("categorys.category.fid",$_REQUEST["fid"]);
				$TPLN->Parse("categorys.category.cid",$row["id"]);
				$TPLN->Loop("categorys.category");
			}
		}
		else
		{
			$TPLN->EraseBloc("categorys");
		}
		return($TPLN->Output());
	}

	/**
	 * Loggs in a user to the system as a guest or if the user has a cookie as a
	 * normal customer.
	 * 
	 * param $db a open database object.
	 * param $sql a open shopsql object.
	 */
	function login($db,$sql)
	{
		if(!isset($_SESSION["customer"]))
		{
			$_SESSION["cart"]=new Cart;
			$cust=new Customer;
			if(isset($_COOKIE["customer"]))
			{
				$cookie=$_COOKIE["customer"];
				$cookieData=split("\\|",$cookie);
				if($sql->autoLogin($db,$cookieData[0],$cookieData[1]))
				{
					$result=$sql->findCustomerByEmail($db,$cookieData[0]);
					if($row=$result->fetchRow())
					{
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
			   		$_SESSION["currency"]=Currency::getInstance();
			   		return;
					}
				}
				else
				{
					removeCookie();
				}
			}
	 		$cust->setName("Guest");
	 		$cust->setType(0);
	 		$cust->setCurrency(getSetting("currency"));
	 		$_SESSION["customer"]=$cust;
	 		$_SESSION["currency"]=Currency::getInstance();
		}
	}

	/**
	 * Checks if the current user has sufficient rights to access a page.
	 * 
	 * param $accesslevel the accesslevel required to access the page. 
	 */
	function checkAccess($accesslevel)
	{
		if(isset($_SESSION["customer"]))
		{
			$cust=$_SESSION["customer"];
			if($cust->getType()>=$accesslevel)
				return(true);
		}
		return(false);
	}

	/**
	 * Saves a cookie on the users computer to remember the login information
	 * and automatically login the user on his next visit.
	 * 
	 * param $db a open database object.
	 * param $sql a open shopsql object.
	 */
	function saveCookie($db,$sql,$email)
	{
		if(isset($_SESSION["customer"]))
		{
			$cust=$_SESSION["customer"];
			if($cust->getType()>0)
			{
				setcookie("customer",$cust->getEmail() . "|" . $sql->getCookiePassword($db,$cust->getEmail()),time()+60*60*24*getSetting("cookieage"));
			}
		}
	}
	
	/**
	 * Removes any cookies set by this site on the clients computer.
	 */
	function removeCookie()
	{
		setcookie("customer",$route,time()-3600);
	}

	/**
	 * Resizes file $src to the size defined in $size (width and height elements) and
	 * saves the new file to $dest.
	 * 
	 * param $src system path to the source file.
	 * param $dest system path to the destination file.
	 * param $size array with two element, width and height, defines the new size.
	 */
	function resizeAndSave($src, $dest, $size)
	{
		list($width,$height)=getimagesize($src);

		$image_p=imagecreatetruecolor($size["width"],$size["height"]);
		$image=imagecreatefromjpeg($src);
		imagecopyresampled($image_p,$image,0,0,0,0,$size["width"],$size["height"],$width,$height);
		imagejpeg($image_p,$dest,100);
	}

?>