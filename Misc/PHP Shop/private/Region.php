<?php
/*
 * Created on 2005-mar-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

	class Region
	{
		private $id;
		private $name;
		
		public function setId($id)
		{
			$this->id=$id;
		}
		
		public function getId()
		{
			return($this->id);
		}
		
		public function setName($name)
		{
			$this->name=$name;
		}
		
		public function getName()
		{
			return($this->name);
		}
		
		public function getData()
		{
			return(array($this->getId(),$this->getName()));
		}
	}
?>
