<?php

function resultToJSON($result, $asObject=false) {
	    
	
    while ($data = $result->fetch_assoc()) {
     $json[] = $data;   
    }
    
    if ($asObject == true) {
    	$json = $json[0];
    }
           
    return $json;
           
}

function connectToDB() {
    $db_host = "localhost"; //Host address (most likely localhost)
		$db_name = "android_kugler"; //Name of Database
		$db_user = "android_kugler"; //Name of database user
		$db_pass = "6aAiX0XiuC"; //Password for database user
		
		$database= new Database($db_host, $db_user, $db_pass, $db_name);
		$db = $database->getConnection();
		return $db;
}

