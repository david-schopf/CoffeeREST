<?php

function resultToJSON($result) {
    
    while ($line = mysql_fetch_assoc($result)) {
     $json[] = $line;   
    }
           
    return $json;
           
}

function connectToDB() {
    $db_host = "localhost"; //Host address (most likely localhost)
		$db_name = "android_kugler"; //Name of Database
		$db_user = "android_kugler"; //Name of database user
		$db_pass = "6aAiX0XiuC"; //Password for database user
		
		$db= new Database($db_host, $db_user, $db_pass, $db_name);
		$db->connect();
}
