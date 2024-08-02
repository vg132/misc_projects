<?php
/*
 * Created on 2005-mar-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 	class ProductGroup
 	{
 		private $id;
 		private $categoryGroupId;
 		private $name;
 		private $picWidth;
 		private $picHeight;
 		private $smallPicWidth;
 		private $smallPicHeight;
 		
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
 		
 		public function setPicWidth($picWidth)
 		{
 			$this->picWidth=$picWidth;
 		}
 		
 		public function getPicWidth()
 		{
 			return($this->picWidth);
 		}
 		
 		public function setPicHeight($picHeight)
 		{
 			$this->picHeight=$picHeight;
 		}
 		
 		public function getPicHeight()
 		{
 			return($this->picHeight);
 		}
 		
 		public function setSmallPicWidth($smallPicWidth)
 		{
 			$this->smallPicWidth=$smallPicWidth;
 		}
 		
 		public function getSmallPicWidth()
 		{
 			return($this->smallPicWidth);
 		}
 		
 		public function setSmallPicHeight($smallPicHeight)
 		{
 			$this->smallPicHeight=$smallPicHeight;
 		}
 		
 		public function getSmallPicHeight()
 		{
 			return($this->smallPicHeight);
 		}
 		
 		public function getData()
 		{
 			return(array($this->getId(),
 										$this->getCategoryGroupId(),
 										$this->getName(),
 										$this->getPicWidth(),
 										$this->getPicHeight(),
 										$this->getSmallPicWidth(),
 										$this->getSmallPicHeight())); 			
 		}
 	}
?>
