<?php
/*
 * Created on 2005-mar-23 by viktor
 * 
 * Version: 1.0
 * Changes:
 */
	require_once("CartItem.php");
	require_once("Item.php");

	class Cart
	{
		private $cart=array();

		public function addItem($item)
		{
			if(isset($this->cart[$item->getId()]))
			{
				$this->cart[$item->getId()]->addQuantity();
			}
			else
			{
				$this->cart[$item->getId()]=$item;
			}
		}

		public function updateQuantity($id, $quantity)
		{
			if($quantity<=0)
				$this->removeItem($id);
			else
				$this->cart[$id]->setQuantity($quantity);
		}

		public function removeItem($id)
		{
			if(isset($this->cart[$id]))
				unset($this->cart[$id]);
		}
		
		public function getTotalPrice()
		{
			$total=0;
			foreach($this->cart as $item)
			{
				$total+=$item->getTotalPrice();
			}
			return($total);
		}
		
		public function getItems()
		{
			return($this->cart);
		}
	}
?>