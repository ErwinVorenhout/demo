<?php 

class Config
{
	private $_config = null;

	public function __construct()
	{
		$this->_config = array('DB_HOST' => 'localhost',
								'DB_DATABASE' => 'demo',
								'DB_PORT' => 3306,
								'DB_USERNAME' => 'root',
								'DB_PASSWORD' => 'root');
	}
	/**
	* Gets the config
	*
	* @return Array $_config
	*/ 

	public function getConfig()
	{
		if(isset($this->_config))
		{
			return $this->_config;
		}
	}
}



?>

