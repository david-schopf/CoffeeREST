<?php
require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/Functions.php";
class User {
	public function test($to) {
		return "success $to";
	}
	public function users() {
		$db = connectToDB ();
		
		$result = $db->query ( "SElECT id, user_name, display_name, email, coffee_count, guthaben FROM coffee_user_users" );
		
		return resultToJSON ( $result );
	}
	public function data($userID) {
		$db = connectToDB ();
		$query = "SElECT id, user_name, display_name, email, coffee_count, guthaben FROM coffee_user_users WHERE id=" . $userID . " LIMIT 1";
		$result = $db->query ( $query );
		
		return resultToJSON ( $result, true );
	}
	public function coffees($userID) {
		$db = connectToDB ();
		
		$result = $db->query ( "SElECT id, preis, timestamp, userID FROM coffee_coffees WHERE userID=" . $userID );
		
		return resultToJSON ( $result );
	}
	public function reinigungen($userID) {
		$db = connectToDB ();
		$query = "SELECT `id`, `termin`, `name`, `status` FROM `android_kugler`.`coffee_reinigungen` WHERE  `id`=$userID;";
		$result = $db->query ( $query );
		
		return resultToJSON ( $result );
	}
	public function postAufladen($userID, $betrag, $code) {
		$db = connectToDB ();
		
		$db->query ( "INSERT INTO `android_kugler`.`coffee_aufladungen` (`userID`, `betrag`, `timestamp`, `code`) VALUES ($userID, $betrag, NOW(), $code); " );
		
		// Update guthaben in user table
		$newGuthaben = updateGuthaben($userID);
		
		return array("guthaben" => $newGuthaben);
	}
	public function postAddBuddy($userID, $buddyID) {
		$db = connectToDB ();
		
		$db->query ( "INSERT INTO `android_kugler`.`coffee_buddies` (`userID`, `buddyID`, `timestamp`) VALUES ({$userID}, {$buddyID}, " . time () . ");" );
		return "Buddy created";
	}
	public function postRemoveBuddy($userID, $buddyID) {
		$db = connectToDB ();
		
		$db->query ( "DELETE FROM `android_kugler`.`coffee_buddies` WHERE `userID`={$userID} AND `buddyID`={$buddyID}" );
		return "Buddy deleted";
	}
	public function buddies($userID) {
		$db = connectToDB ();
		$query = "SELECT id, user_name, display_name, email, coffee_count, guthaben FROM coffee_user_users WHERE id IN (SELECT buddyID FROM `coffee_buddies` WHERE userID={$userID})";
		
		$result = $db->query ( $query );
		
		return resultToJSON ( $result );
	}
	public function loggedin($username) {
		$db = connectToDB ();
		$query = "SElECT id, user_name, display_name, email, coffee_count, guthaben FROM coffee_user_users WHERE `user_name`='" . $username . "' OR `email`='" . $username . "'  LIMIT 1";
		$result = $db->query ( $query );
		
		return resultToJSON ( $result, true );
	}
	
}