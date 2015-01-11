<?php
require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/Functions.php";
class User {
	public function test($to) {
		return "success $to";
	}
	public function users() {
		$db = connectToDB ();
		
		$result = $db->query ( "SElECT id, user_name, display_name, email, coffee_count, guthaben, sign_up_stamp FROM coffee_user_users" );
		
		return resultToJSON ( $result );
	}
	public function data($userID) {
		$db = connectToDB ();
		$query = "SElECT id, user_name, display_name, email, coffee_count, guthaben, sign_up_stamp FROM coffee_user_users WHERE id=" . $userID . " LIMIT 1";
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
		
		$betrag = $betrag * 100;
		
		$db = connectToDB ();
		$db->query ( "INSERT INTO `android_kugler`.`coffee_aufladungen` (`userID`, `betrag`, `timestamp`, `code`, `verified`) VALUES ($userID, $betrag, ".time().", $code, 0); " );
		
		return array("success" => $code);
	}
	
	public function verifyCode($userID, $code) {
		
		$db = connectToDB ();
		$query = "SELECT `id` FROM `coffee_aufladungen` WHERE `userID`=" . $userID." AND `code`='{$code}'";
		$result = $db->query($query);
		if ($result != null) {
			$row = $result->fetch_assoc();
			$id = $row['id'];
		}
		// Kein Code gefunden
		if (!$id) {
			return array("error" => "Code falsch");
		} 
		
		// Code verify
		$verifyQuery = "UPDATE `android_kugler`.`coffee_aufladungen` SET `verified` = '1' WHERE `coffee_aufladungen`.`id` = {$id}";
		$db->query($verifyQuery);
		
		// Update guthaben in user table
		$newGuthaben = updateGuthaben ( $userID );
		
		return array (
				"guthaben" => $newGuthaben
		);	
	}
	
	public function aufladungen($userID) {
		$db = connectToDB ();
		$query = "SELECT `id`,`userID`,`betrag`,`timestamp`,`code` FROM `coffee_aufladungen` WHERE `userID`=" . $userID." AND `verified`=1";
		$result = $db->query ( $query );
		return resultToJSON ( $result );
	}
	public function postAddBuddy($userID, $buddyID) {
		$db = connectToDB ();
		
		$db->query ( "INSERT INTO `android_kugler`.`coffee_buddies` (`userID`, `buddyID`, `timestamp`) VALUES ({$userID}, {$buddyID}, " . time () . ");" );
		return array (
				"success" => "Buddy created" 
		);
	}
	public function postRemoveBuddy($userID, $buddyID) {
		$db = connectToDB ();
		
		$db->query ( "DELETE FROM `android_kugler`.`coffee_buddies` WHERE `userID`={$userID} AND `buddyID`={$buddyID}" );
		return array (
				"success" => "Buddy deleted" 
		);
	}
	public function buddies($userID) {
		$db = connectToDB ();
		$query = "SELECT id, user_name, display_name, email, coffee_count, guthaben, sign_up_stamp  FROM coffee_user_users WHERE id IN (SELECT buddyID FROM `coffee_buddies` WHERE userID={$userID})";
		
		$result = $db->query ( $query );
		
		return resultToJSON ( $result );
	}
	public function loggedin($username) {
		$db = connectToDB ();
		$query = "SElECT id, user_name, display_name, email, coffee_count, guthaben, sign_up_stamp  FROM coffee_user_users WHERE `user_name`='" . $username . "' OR `email`='" . $username . "'  LIMIT 1";
		$result = $db->query ( $query );
		
		return resultToJSON ( $result, true );
	}
	public function history($userID) {
		$db = connectToDB ();
		
		$nameQuery = "SElECT `display_name` FROM coffee_user_users WHERE id=" . $userID . " LIMIT 1";
		$result = $db->query ( $nameQuery );
		$row = $result->fetch_assoc ();
		$displayname = $row ['display_name'];
		
		$coffeesQ = "SELECT `id`,`preis`,`typ`, `timestamp`, `userID` FROM `android_kugler`.`coffee_coffees` WHERE `userID`={$userID} ORDER BY `timestamp` DESC";
		$aufladungenQ = "SELECT `id`,`userID`,`betrag`,`timestamp`,`code` FROM `coffee_aufladungen` WHERE `userID`=" . $userID. " AND `verified`=1";
		$reinigungenQ = "SELECT `id`, `termin`, `name`, `status`,`timestamp` FROM `android_kugler`.`coffee_reinigungen` WHERE  `name`='" . $displayname . "'";
		
		$coffees = resultToJSON ( $db->query ( $coffeesQ ) );
		$aufladungen = resultToJSON ( $db->query ( $aufladungenQ ) );
		$reinigungen = resultToJSON ( $db->query ( $reinigungenQ ) );
		
		return array (
				"coffees" => $coffees,
				"aufladungen" => $aufladungen,
				"reinigungen" => $reinigungen 
		);
	}
}