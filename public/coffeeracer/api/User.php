<?php

require_once __DIR__."/Database.php";
require_once __DIR__."/Functions.php";

class User {
    
    function test() {
        return "success";
    }
	
	function users() {
        
		
        connectToDB();
        
        $result = mysql_query("SElECT id, user_name, display_name, email, coffee_count, guthaben FROM coffee_user_users");
        
        
        
        return resultToJSON($result);
        
        
	}
    
    function user($id) {
        
        connectToDB();
        
        $result = mysql_query("SElECT id, user_name, display_name FROM coffee_user_users WHERE id=$id");
                
        return resultToJSON($result);
        
    }

    function coffees($id) {
         connectToDB();
        
        $result = mysql_query("SElECT id, preis, timestamp FROM coffee_user_users WHERE userID=$id");
                
        return resultToJSON($result);
        
    }

}