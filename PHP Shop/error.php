<?php
	/**
	 * Created on 2005-mar-24 by viktor
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

	$TPLN->Open("private/templates/error.tpl");
	
	$TPLN->Parse("head",getHead("Home"));
	$TPLN->Parse("header",getHeader());
	$TPLN->Parse("menu",getMenu($db,$sql));

	$TPLN->Parse("error_message",$_REQUEST["error"]);

	$db->close();
	
	$TPLN->Write();
?>