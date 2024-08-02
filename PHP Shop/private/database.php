<?php
	require_once("DB.php");
	require_once("Configuration.php");

	class Database
	{
		private $dsn;
		private $config;
		private $conn;

		function Database()
		{
			$this->config=Configuration::getInstance();
			$this->dsn=$this->createDSN();
		}

		function connect()
		{
			$this->conn=&DB::connect($this->dsn);
			if(DB::isError($this->conn))
			{
    		echo 'Database error: ' . $this->conn->getMessage() . "<br/>";
    		echo 'Error code: ' . $this->conn->getCode() . "<br/>";
    		echo 'DBMS/Debug Message: ' . $this->conn->getDebugInfo() . "<br/>";
    		exit;
			}
			$this->conn->setFetchMode(DB_FETCHMODE_ASSOC);
		}

		function executeQuery($query)
		{
			$result=$this->conn->query($query);
			if(DB::isError($result))
			{
    		echo 'Database error: ' . $result->getMessage() . "<br/>";
    		echo 'Error code: ' . $result->getCode() . "<br/>";
    		echo 'DBMS/Debug Message: ' . $result->getDebugInfo() . "<br/>";
    		exit;
			}
			return($result);
		}

		function executeUpdate($query)
		{
			$result=$this->conn->query($query);
			if(DB::isError($result))
			{
    		echo 'Database error: ' . $result->getMessage() . "<br/>";
    		echo 'Error code: ' . $result->getCode() . "<br/>";
    		echo 'DBMS/Debug Message: ' . $this->conn->getDebugInfo() . "<br/>";
    		exit;
			}
			return($result);
		}

		function prepareStatement($statement)
		{
			return($this->conn->prepare($statement));
		}

		public function executeStatement($statement, $data)
		{
			$result=&$this->conn->execute($statement,$data);
			if(DB::isError($result))
			{
    		echo 'Database error: ' . $result->getMessage() . "<br/>";
    		echo 'Error code: ' . $result->getCode() . "<br/>";
    		echo 'DBMS/Debug Message: ' . $result->getDebugInfo() . "<br/>";
    		exit;
			}
			return($result);
		}

		function close()
		{
			$this->conn->disconnect();
		}

		function createDSN()
		{
			return(array(
					'phptype'  => $this->config->Get('dbtype'),
					'username' => $this->config->Get('dbuser'),
					'password' => $this->config->Get('dbpass'),
					'hostspec' => $this->config->Get('dbserver'),
					'database' => $this->config->Get('db'),
					'port' => $this->config->Get('dbport')
				));
		}
		
		function getNextID($sequence)
		{
			$id = $this->conn->nextId($sequence);
			if(PEAR::isError($id))
				die($id->getMessage());
			return($id);
		}
	}
?>
