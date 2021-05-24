<?php namespace Models;

require_once('Config/AppSettings.php');
use Config\AppSettings;

class Connection{

    private $host;
	private $user;
	private $password = "";
	private $database;
	private $connection;
    private $AppSettings;

    public function __construct(){
        (new AppSettings(__DIR__ . '/../.env'))->load(); 
        $this->host         = getenv('HOST_DATABASE');      
        $this->database     = getenv('DATABASE_NAME');
        $this->user         = getenv('DATABASE_USER');
        $this->password     = getenv('DATABASE_PASSWORD');
		$this->connection = new \PDO("mysql:host=".$this->host."dbname=".$this->database,$this->user,$this->password);
    }

    public function _prepare_($sql){
		if($this->connection){
			$data = $this->connection->prepare($sql);
			return $data;	
		}
	}

	public function proteccion($text){
		if($this->connection){
			$string = $this->connection->quote($text);
			return $string;
		}
	}

}

?>