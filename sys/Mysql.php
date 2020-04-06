<?php 
/**
 * DatabaseConnector
 *
 * 
 *
 * 
 */ 
class DatabaseConnector {

	private $dbConnection = null;
	

	public function __construct()
	{
		require('config.php');

		$config = (new Config())->getConfig();
	
		$host = $config['DB_HOST'];
		$port = $config['DB_PORT'];
		$db   = $config['DB_DATABASE'];
		$user = $config['DB_USERNAME'];
		$pass = $config['DB_PASSWORD'];

		try {
			$this->dbConnection = new \PDO(
				"mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db",
				$user,
				$pass
			);
		} catch (\PDOException $e) {
			print "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db";
			exit($e->getMessage());
		}
	}

	/**
	 * Gets the database connection
	 *
	 *
	 * @return dbConnection dbConnection
	 */ 

	public function getConnection()
	{
		return $this->dbConnection;
	}
}

 ?>