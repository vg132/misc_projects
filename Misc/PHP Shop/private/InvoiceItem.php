<?php
	/**
	 * Created on 2005-mar-24 by viktor
	 * 
	 * Version: 1.0
	 * Changes:
	 */
	
	class InvoiceItem
	{
		private $id;
		private $invoiceId;
		private $itemId;
		private $quantity;
		private $price;

		public function setId($id)
		{
			$this->id=$id;
		}

		public function getId()
		{
			return($this->id);
		}

		public function setInvoiceId($invoiceId)
		{
			$this->invoiceId=$invoiceId;
		}

		public function getInvoiceId()
		{
			return($this->invoiceId);
		}

		public function setItemId($itemId)
		{
			$this->itemId=$itemId;
		}

		public function getItemId()
		{
			return($this->itemId);
		}

		public function setQuantity($quantity)
		{
			$this->quantity=$quantity;
		}

		public function getQuantity()
		{
			return($this->quantity);
		}

		public function setPrice($price)
		{
			$this->price=$price;
		}

		public function getPrice()
		{
			return($this->price);
		}
		
		public function getData()
		{
			return(array($this->getId(),
										$this->getInvoiceId(),
										$this->getItemId(),
										$this->getQuantity(),
										$this->getPrice()));
		}
	}
?>
