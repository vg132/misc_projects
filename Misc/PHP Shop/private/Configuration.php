<?php
	class Configuration
	{
		const CONFIG_FILE = "/www/php.vgsoftware.com/shop/private/shop-config.ini";
		static private $instance=null;
		private $ini_settings;

		private function __construct()
		{
			if(file_exists(self::CONFIG_FILE))
			{
				$this->ini_settings=parse_ini_file(self::CONFIG_FILE);
			}
		}

		public function getInstance()
		{
			if(self::$instance==null)
			{
				self::$instance=new Configuration();
			}
			return(self::$instance);
		}

		public function get($name)
		{
			if(isset($this->ini_settings[$name]))
			{
				return($this->ini_settings[$name]);
			}
			else
			{
				return(null);
			}
		}
	}

?>