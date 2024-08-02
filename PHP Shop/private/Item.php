<?php
	/*
	** File: Item.php
	** Description: Contains all values of a item
	** Version: 1.0
	** Created: 2005-03-18
	** Author: Viktor Gars
	** Email: viktor@vgsoftware.com
	**
	** Copyright (c) 2005 VG Software
	*/
	class Item
	{
		private $id;
		private $categoryId;
		private $productGroupId;
		private $regionId;
		private $rrp;
		private $price;
		private $name;
		private $description;
		private $picture;
		private $smallPicture;
		private $releaseDate;

		public function setId($id)
		{
			$this->id=$id;
		}

		public function getId()
		{
			return($this->id);
		}

		public function setCategoryId($categoryId)
		{
			$this->categoryId=$categoryId;
		}

		public function getCategoryId()
		{
			return($this->categoryId);
		}

		public function setProductGroupId($productGroupId)
		{
			$this->productGroupId=$productGroupId;
		}

		public function getProductGroupId()
		{
			return($this->productGroupId);
		}

		public function setRegionId($regionId)
		{
			$this->regionId=$regionId;
		}

		public function getRegionId()
		{
			return($this->regionId);
		}

		public function setRRP($rrp)
		{
			$this->rrp=$rrp;
		}

		public function getRRP()
		{
			return($this->rrp);
		}

		public function setPrice($price)
		{
			$this->price=$price;
		}

		public function getPrice()
		{
			return($this->price);
		}

		public function setName($name)
		{
			$this->name=$name;
		}

		public function getName()
		{
			return($this->name);
		}

		public function setDescription($description)
		{
			$this->description=$description;
		}

		public function getDescription()
		{
			return($this->description);
		}

		public function setPicture($picture)
		{
			$this->picture=$picture;
		}

		public function getPicture()
		{
			return($this->picture);
		}

		public function setSmallPicture($smallPicture)
		{
			$this->smallPicture=$smallPicture;
		}

		public function getSmallPicture()
		{
			return($this->smallPicture);
		}

		public function setReleaseDate($releaseDate)
		{
			$this->releaseDate=$releaseDate;
		}

		public function getReleaseDate()
		{
			return($this->releaseDate);
		}

		/**
		 * Returns a array with the data placed in the same order as every update and
		 * insert statement should be ordered.
		 * 
		 * @return array the item data 
		 */
		public function getData()
		{
			return(array($this->getId(),
									$this->getName(),
									$this->getDescription(),
									$this->getRRP(),
									$this->getProductGroupId(),
									$this->getCategoryId(),
									$this->getRegionId(),
									$this->getPicture(),
									$this->getSmallPicture(),
									$this->getReleaseDate()));
		}
	}
?>