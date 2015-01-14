<?php

require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/Functions.php";
require_once __DIR__ . "/GCMPushMessage.php";

class Alarm {
	
	private static $API_KEY = "AIzaSyCPdoZnn8U8LJ_j_HAhOKglt8Z3JEw2fVk";
	
	public function send($userID) {
		$db = connectToDB ();
		$query = "SElECT `deviceID` FROM coffee_user_users WHERE id IN (SELECT buddyID FROM `coffee_buddies` WHERE userID={$userID})";
		
		$result = $db->query($query);
		
		while ( $data = $result->fetch_assoc () ) {
			$devices [] = $data ['deviceID'];
		}
		
		$userQ = "SElECT `display_name` FROM coffee_user_users WHERE id={$userID} LIMIT 1";			
		$userD = resultToJSON($db->query($userQ), true);	
		$username = $userD['display_name'];	
		
		$message = $username." will mit dir einen Kaffee trinken";
		
		$gcmPush = new GCMPushMessage ( self::$API_KEY );
		$gcmPush->setDevices ( $devices );
		
		$answer = stripcslashes($gcmPush->send ( $message ));
		return json_decode($answer);
	}
	
	public function sendfriend($userID, $buddyID) {
		$db = connectToDB ();
		$query = "SElECT `deviceID` FROM coffee_user_users WHERE id={$buddyID} LIMIT 1";
		
		$result = $db->query($query);
		
		while ( $data = $result->fetch_assoc () ) {
			$devices [] = $data ['deviceID'];
		}
		
		$userQ = "SElECT `display_name` FROM coffee_user_users WHERE id={$userID} LIMIT 1";			
		$userD = resultToJSON($db->query($userQ), true);	
		$username = $userD['display_name'];	
		
		$message = $username." will mit dir einen Kaffee trinken";
		
		$gcmPush = new GCMPushMessage ( self::$API_KEY );
		$gcmPush->setDevices ( $devices );
		
		$answer = stripcslashes($gcmPush->send ( $message ));
		return json_decode($answer);
	}
	
	
	public function postRegisterDevice($userID, $deviceID) {
		$db = connectToDB ();
		$db->query ( "UPDATE `android_kugler`.`coffee_user_users` SET `deviceID`='{$deviceID}' WHERE `id`={$userID}" );
		
		return array("success" => "Device registered");
	}
}