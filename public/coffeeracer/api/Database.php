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
	}
	public function connect() {
		$this->connection = mysql_connect ( $thus->server, $this->user, $this->pass );
		if ($this->connection) {
			mysql_select_db ( $this->db );
		}
		
		return $this->connection;
	}
	
	public function getConnection() {
		if (!$this->connection) {
	 connect();
		}
		return $this->connection;
	
		
	}
    
    
	
	
}





?>