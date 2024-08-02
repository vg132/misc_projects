<?php
	require_once('functions.php');

	class Currency
	{
		static private $instance = NULL;
		private $lastupdate=0;
		private $currency=array();

		private function __construct()
		{
			$this->update();
		}

		public function getInstance()
		{
			if(self::$instance==NULL)
				self::$instance=new Currency();
			return(self::$instance);
		}

		function getCurrencies()
		{
			return(array_keys($this->currency));
		}

		function getPrice($currency, $price)
		{
			if(time()>($this->lastupdate+(GetSetting('currency_update')*60)))
				$this->update();
			return(round($price*$this->currency[strtoupper($currency)],2));
		}
	
		private function update()
		{
			$xml_parser = xml_parser_create();
			xml_set_element_handler($xml_parser, array(&$this,'startElement'), array(&$this,'endElement'));
			$fp=fopen(getSetting('currency_rates'),'r') or die('Error reading RSS data.');
			while ($data = fread($fp, 4096)) 
				xml_parse($xml_parser, $data, feof($fp)) or die(sprintf('XML error: %s at line %d',xml_error_string(xml_get_error_code($xml_parser)),xml_get_current_line_number($xml_parser))); 
			fclose($fp);
			xml_parser_free($xml_parser);
			
			$this->currency[strtoupper(getSetting('currency'))]='1.0';

			$this->lastupdate=time();
		}
		
		function startElement($parser, $name, $attrs)
		{
			if(($name=='CUBE')&&(isset($attrs['CURRENCY'])))
			{
				$this->currency[strtoupper($attrs['CURRENCY'])]=$attrs['RATE'];
			}
		}
		
		function endElement($parser, $name)
		{
		}
	}
?>