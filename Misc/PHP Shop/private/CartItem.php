<?php
	/**
	 * Created on 2005-mar-23 by viktor
	 * 
	 * Version: 1.0
	 * Changes:
	 */
	require_once("Item.php");

	class CartItem extends Item
	{
		private $quantity;
		
		public function setQuantity($quantity)
		{
			$this->quantity=$quantity;
		}
		
		public function addQuantity()
		{
			$this->quantity++;
		}
		
		public function getQuantity()
		{
			return($this->quantity);
		}
		
		public function getTotalPrice()
		{
			return($this->getQuantity()*$this->getPrice());
		}
	}
?>
