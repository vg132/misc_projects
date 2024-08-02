<?php
	require_once("private/functions.php");
	require_once("private/database.php");
	require_once("private/shopsql.php");
	require_once("private/Invoice.php");
	require_once("private/InvoiceItem.php");

	session_start();

	$db=new Database;
	$sql=new ShopSQL;

	$db->connect();

	login($db,$sql);

	$cart=$_SESSION["cart"];
	$cust=$_SESSION["customer"];

	//Check if user is logged in, if not then redirect to error page
	if($cust->getType()<=0)
	{
		$db->close();
		header("Location: " . getSetting("baseurl") . "error.php?error=You have to login to checkout your cart.");
		exit();
	}

	$orderText="";
	//Add this order to the database
	$invoice=new Invoice;
	$invoice->setCustomer($cust->getId());
	$invoice->setDate(NULL);
	$invoice->setTotalPrice($cart->getTotalPrice());
	if($sql->addInvoice($db,$invoice)==1)
	{
		//if the order was added then add all items for this order
		$items=$cart->getItems();
		foreach($items as $item)
		{
			$invoiceItem=new InvoiceItem;
			$invoiceItem->setInvoiceId($invoice->getId());
			$invoiceItem->setItemId($item->getId());
			$invoiceItem->setQuantity($item->getQuantity());
			$invoiceItem->setPrice($item->getPrice());
			$sql->addInvoiceItem($db,$invoiceItem);
			$orderText=$orderText . $item->getName() . " - " . $item->getPrice() . "\n";
		}
		$orderText=$orderText . "Total price - " . $cart->getTotalPrice();
	}
	else
	{
		//if the order was not added print a error message for the user
		$db->close();
		header("Location: " . getSetting("baseurl") . "error.php?error=Database error when adding your order. No order has been sent, please contact ower support.");
		exit();	
	}
	$db->close();

	//Send a email confirming the order
	$TPLN=new TPLN;
	$TPLN->Open("private/templates/mail/order.tpl");
	$TPLN->Parse("name",getSetting("pagename"));
	$TPLN->Parse("items",$orderText);
	mail($cust->getEmail(),"Order Confirmation - " . getSetting("pagename"),$TPLN->Output());
	header("Location: " . getSetting("baseurl") . "index.php");
	exit();
?>
