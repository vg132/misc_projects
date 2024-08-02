<?php
/*
 * Created on 2005-mar-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
	class Category
	{
		private $id;
		private $categoryGroupId;
		private $name;
		
		public function setId($id)
		{
			$this->id=$id;
		}
		
		public function getId()
		{
			return($this->id);
		}
		
		public function setCategoryGroupId($categoryGroupId)
		{
			$this->categoryGroupId=$categoryGroupId;
		}
		
		public function getCategoryGroupId()
		{
			return($this->categoryGroupId);
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
			return(array($this->getId(),
										$this->getCategoryGroupId(),
										$this->getName()));
		}
	}
?>
