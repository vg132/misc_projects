<?php
	/**
	 * Created on 2005-mar-24 by viktor
	 * 
	 * Version: 1.0
	 * Changes:
	 */
	 
	class Invoice
	{
		private $id;
		private $customer;
		private $date;
		private $totalPrice;
		
		public function setId($id)
		{
			$this->id=$id;
		}
		public function getId()
		{
			return($this->id);
		}
		
		public function setCustomer($customer)
		{
			$this->customer=$customer;
		}

		public function getCustomer()
		{
			return($this->customer);
		}

		public function setDate($date)
		{
			$this->date=$date;
		}

		public function getDate()
		{
			return($this->date);
		}

		public function setTotalPrice($totalPrice)
		{
			$this->totalPrice=$totalPrice;
		}

		public function getTotalPrice()
		{
			return($this->totalPrice);
		}
		
		public function getData()
		{
			return(array($this->getId(),
										$this->getCustomer(),
										$this->getDate(),
										$this->getTotalPrice()));
		}
	}
?>
