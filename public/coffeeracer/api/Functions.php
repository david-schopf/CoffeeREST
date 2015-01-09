<?php

use Luracast\Restler\Data\Object;
function resultToJSON($result, $asObject=false) {
	    
	$json = array();
	
    while ($data = $result->fetch_assoc()) {
     $json[] = $data;   
    }
    
    if ($asObject == true) {
    	$json = $json[0];
    }
    
    // Prevent errors when result is empty
    if ($json == array()) {
    	$json = ($asObject)?new Object():array(); 
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

function updateGuthaben($userID) {
	$db = connectToDB ();

	$guthabenQuery = "SELECT SUM(betrag) FROM `coffee_aufladungen` WHERE `userID`={$userID}";
	$coffeeQuery = "SELECT SUM(preis) FROM `coffee_coffees` WHERE `userID`={$userID}";

	$guthaben = $db->query($guthabenQuery);
	$ausgaben = $db->query($coffeeQuery);

	$guthabenInt = $guthaben->fetch_row()[0];
	$ausgabenInt = $ausgaben->fetch_row()[0];

	$newGuthaben = $guthabenInt - $ausgabenInt;
	
	$db->query ("UPDATE `android_kugler`.`coffee_user_users` SET `guthaben`={$newGuthaben} WHERE  `id`={$userID};");
	
	return $newGuthaben;
}
