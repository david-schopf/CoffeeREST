<?php

require_once __DIR__."/Database.php";
require_once __DIR__."/Functions.php";

class User {
    
    public function test($to) {
        return "success $to";
    }
	
	public function users() {
        
		
        $db = connectToDB();


        $result = $db->query("SElECT id, user_name, display_name, email, coffee_count, guthaben FROM coffee_user_users");
        
          
        return resultToJSON($result);
        
        
	} 
    
    public function data($userID) {

        
        $db = connectToDB();
        $query = "SElECT id, user_name, display_name, email, coffee_count, guthaben FROM coffee_user_users WHERE id=".$userID;
        $result = $db->query($query);
                
        return resultToJSON($result);
        
    }

    public function coffees($userID) {
         $db = connectToDB();
        
        $result = $db->query("SElECT id, preis, timestamp, userID FROM coffee_coffees WHERE userID=".$userID);
                
        return resultToJSON($result);
        
    }  

    public function reinigungen($userID) {

        $db = connectToDB();
        $query = "SELECT `id`, `termin`, `name`, `status` FROM `android_kugler`.`coffee_reinigungen` WHERE  `id`=$userID;";
        $result = $db->query($query);
                
        return resultToJSON($result);
    }

    public function postAufladen($userID, $betrag, $code) {

        $db = connectToDB();

        $db->query("INSERT INTO `android_kugler`.`coffee_aufladungen` (`userID`, `betrag`, `timestamp`, `code`) VALUES ($userID, $betrag, NOW(), $code); ")

        return "Aufgeladen";
    }

}