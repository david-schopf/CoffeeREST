<?php
class Database {
	private $server;
	private $user;
	private $pass;
	private $db;
	private $connection;
	
	public function __construct($server, $user, $pass, $db) {
		$this->server = $server;
		$this->user = $user;
		$this->pass = $pass;
		$this->db = $db;
		$this->connect();
	}

	private function connect() {
		$this->connection =  new mysqli($this->server, $this->user, $this->pass, $this->db);
	}
	
	public function getConnection() {
		return $this->connection;	
	}
    
    
	
	
}





?>