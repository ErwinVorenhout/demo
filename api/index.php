<?php 

define('POST_METHOD','POST');
define('GET_METHOD','GET');

class Result
{
	private $_data;
	private $_meta;
	public $result;

	public function __construct($data,$success,$message = null)
	{
		$this->_meta['result'] = $success;
		$this->_data = $data;

		if($this->_data == null)
		{
			$this->_data = array();		
		}
		if($message != null)
		{
			$this->_meta['msg'] = $message;
		}

		$result = array('meta' => array('success' => $success,'count' => count($this->_data),'message' => $message) ,'data' => $this->_data);
		
		$this->result = $result;
	}

	/**
	 * getResult 
	 * 
	 * This method will return the result
	 * 
	 * 
	 *
	 * @return Array() Result
	 */ 

	public function getResult()
	{
		return $this->result;
	}
}

class Api
{
	private $_dbConnection;
	private $_translation;
	public function __construct()
	{
		require('../sys/Mysql.php');
		require('translate.php');

		$this->_dbConnection = new DatabaseConnector();
		$this->_translation = new Translate('nl');
	}
	/**
	 * insert 
	 * 
	 * This method will check if all the neccesary fields are filled, if yes it will insert the records in the database.
	 * 
	 * 
	 * @param array $requestData
	 * @return Array() Result
	 */ 
	public function insert($requestData)
	{
		$sanitizedData = $this->_sanitizeData($requestData);
		

		if(isset($sanitizedData) && !empty($sanitizedData))
		{
			if($this->_checkMethod(POST_METHOD))
			{				
				$forgottenFields = $this->_checkInsert($sanitizedData);				
				if($forgottenFields == null)
				{	
					$sql = "INSERT INTO registrations (salution, firstName, lastName,prefix,email,country_code) VALUES (:salution, :firstName, :lastName,:prefix,:email,:country_code)";
					$stmt= $this->_dbConnection->getConnection()->prepare($sql);

					if($stmt->execute($sanitizedData))
					{	
						$result = new Result(null,true);
						return $result->getResult();
					}
					else
					{
						 $result = new Result(null,false,'Er is iets misgegaan, probeer het later nog eens');
						 return $result->getResult();
					}
				}
				else
				{

					$result = new Result(null,false,'De volgende velden dienen nog ingevuld te worden: '. implode(',', $forgottenFields));	
					return $result->getResult();
				}
			}
		}
	}
	/**
	 * _sanitizeData 
	 * 
	 * This method will get all registrations 
	 * 
	 * 
	 * @param array $dataSet
	 * @return Array() sanitizedData
	 */ 

	public function getRegistrations() : array
	{
		if($this->_checkMethod(GET_METHOD))
		{
			$sql = 'SELECT salution,firstName,lastName,prefix,email,country_code FROM registrations';
			$sth = $this->_dbConnection->getConnection()->prepare($sql); 		
			$sth -> execute();

			$data = $sth->fetchAll(PDO::FETCH_OBJ);
			$result = new Result($data,true);

			return $result->getResult();
		}
	}


	/**
	 * _sanitizeData 
	 * 
	 * This method will remove tags from given entry.
	 * 
	 * 
	 * @param array $dataSet
	 * @return Array() sanitizedData
	 */ 
	private function _sanitizeData($dataSet) : array
	{
		$sanitizedData = array();

		foreach ($dataSet as $key => $value)
		{
			$sanitizedData[$key] = strip_tags($value);
		}
		return $sanitizedData;
	}
	/**
	 * _sanitizeData 
	 * 
	 * This method will check if all inserted data is there, and translates the data
	 * 
	 * 
	 * @param array $data
	 * @return  array forgottenFields
	 */ 
	private function _checkInsert($data)
	{
		$forgottenFields = array();
		$fields = array('salution', 'firstName', 'lastName','prefix','email','country_code');	

		// check if all fields are filled in.
		foreach($fields as $field)
		{		
			if(empty($data[$field]))
			{				
				array_push($forgottenFields, $this->_translation->getTranslation($field));
			}
		}
		if(!empty($forgottenFields))
		{
			return $forgottenFields;
		}
		return null;
	}

	/**
	 * Check if the given method is valid
	 *
	 *
	 * @return bool 
	 */ 
	private function _checkMethod($method) : bool
	{		
		if($_SERVER['REQUEST_METHOD'] == $method)
		{
			return true;
		}
	}
}

class RequestHandler
{
	private $_request;
	private $path;

	/**
	 * handleRequest 
	 * 
	 * This method will check if the request is a valid request, and will handle the request.
	 * 
	 * 
	 * @param $_SERVER['REQUEST_URI'] $request
	 * @return Array() Result
	 */ 
	public function handleRequest($request)
	{
		if(isset($request))
		{
			$this->_request = $request;
			$request = $this->analyzeRequest();
			$api = new Api();

			if(method_exists($api, $request))
			{
				$result = $api->$request($_POST);
				return $result;
			}
		}
		return false;
	}

	/**
	 * analyzeRequest 
	 * 
	 * This method will get the path of the request.
	 * 
	 * 
	 * @param $_SERVER['REQUEST_URI'] $request
	 * @return Array() Result
	 */ 
	private function analyzeRequest() : string
	{
		$requestUri = strtolower($_SERVER['REQUEST_URI']);
		$path = explode('/',$requestUri)[2];
		return $path;
	}
}



$RequestHandler = new RequestHandler();


$result = $RequestHandler->handleRequest($_SERVER);
print json_encode($result);


 ?>