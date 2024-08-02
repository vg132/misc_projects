<?php
/*
 * Created on 2005-mar-21 by viktor
 * 
 * Version: 1.0
 * Changes:
 */
 
	class Customer
	{
		private $id;
		private $name;
		private $email;
		private $password;
		private $address;
		private $postCode;
		private $state;
		private $city;
		private $type;
		private $country;
		private $countryId;
		private $currency;

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

		public function setEmail($email)
		{
			$this->email=$email;
		}

		public function getEmail()
		{
			return($this->email);
		}

		public function setPassword($password)
		{
			$this->password=$password;
		}

		public function getPassword()
		{
			return($this->password);
		}

		public function setAddress($address)
		{
			$this->address=$address;
		}

		public function getAddress()
		{
			return($this->address);
		}
		
		public function setPostCode($postCode)
		{
			$this->postCode=$postCode;
		}

		public function getPostCode()
		{
			return($this->postCode);
		}
		
		public function setState($state)
		{
			$this->state=$state;
		}
		
		public function getState()
		{
			return($this->state);
		}
		
		public function setCity($city)
		{
			$this->city=$city;
		}
		
		public function getCity()
		{
			return($this->city);
		}
		
		public function setType($type)
		{
			$this->type=$type;
		}
		
		public function getType()
		{
			return($this->type);
		}
		
		public function setCountry($country)
		{
			$this->country=$country;
		}
		
		public function getCountry()
		{
			return($this->country);
		}
		
		public function setCurrency($currency)
		{
			$this->currency=$currency;
		}
		
		public function getCurrency()
		{
			return($this->currency);
		}

		public function setCountryId($countryId)
		{
			$this->countryId=$countryId;
		}

		public function getCountryId()
		{
			return($this->countryId);
		}

		public function getData()
		{
			return(array($this->getId(),
										$this->getEmail(),
										$this->getPassword(),
										$this->getName(),
										$this->getAddress(),
										$this->getCity(),
										$this->getState(),
										$this->getPostCode(),
										$this->getType(),
										$this->getCountry(),
										$this->getCurrency()));
		}
	}
?>
