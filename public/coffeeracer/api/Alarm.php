<?php
use Luracast\Restler\User;
require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/Functions.php";
require_once __DIR__ . "/GCMPushMessage.php";

class Alarm {
	
	private static $API_KEY = "AIzaSyCPdoZnn8U8LJ_j_HAhOKglt8Z3JEw2fVk";
	public function postSend($userID) {
		$db = connectToDB ();
		$query = "SElECT `deviceID` FROM coffee_user_users WHERE deviceID IS NOT NULL";
		
		$result = $db->query($query);
		
		while ( $data = $result->fetch_assoc () ) {
			$devices [] = $data ['deviceID'];
		}
		
		$userQ = "SElECT `display_name` FROM coffee_user_users WHERE id={userID} LIMIT 1";		
		$userR = $db->query($userQ);
		$userD = $userR->fetch_assoc();		
		$username = $userD['display_name'];	
		
		$message = $username." will mit dir einen Kaffee trinken";
		
		$gcmPush = new GCMPushMessage ( self::$API_KEY );
		$gcmPush->setDevices ( $devices );
		
		return $gcmPush->send ( $message );
	}
	public function postRegisterDevice($userID, $deviceID) {
		$db = connectToDB ();
		$db->query ( "UPDATE `android_kugler`.`coffee_user_users` SET `deviceID`='{$deviceID}' WHERE `id`={$userID}" );
		
		return "Device registered";
	}
}