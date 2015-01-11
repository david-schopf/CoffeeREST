<?php

require_once __DIR__."/Database.php";
require_once __DIR__."/Functions.php";

class Reinigung {

	const OFFEN = 0;
	const ZUGEWIESEN = 1;
	const ERLEDIGT = 2;


	public function postCreate($termin, $timestamp, $name) {
		$db = connectToDB();
		$query = "INSERT INTO `android_kugler`.`coffee_reinigungen` (`termin`, `name`, `status`, `timestamp`) VALUES ({$termin}, '{$name}', '1', {$timestamp})";
		$msg = $db->query($query);
		return array("success" => $msg);
	}
	
	public function byuser($displayname) {
		$db = connectToDB();
		$reinigungenQ = "SELECT `id`, `termin`, `name`, `status`,`timestamp` FROM `android_kugler`.`coffee_reinigungen` WHERE `name`='".$displayname."'";
		$result = $db->query($reinigungenQ);
		
		return resultToJSON($result);
	}

	function termine($status) {
		$db = connectToDB();
		$result = $db->query("SELECT `id`, `termin`, `name`, `status`,`timestamp` FROM coffee_reinigungen WHERE status=".$status." ORDER BY termin DESC");
		return resultToJSON($result);
	}
	
	function history() {
		$db = connectToDB();
		$result = $db->query("SELECT `id`, `termin`, `name`, `status`,`timestamp` FROM coffee_reinigungen ORDER BY termin DESC");
		return resultToJSON($result);
	}




}
    

   ?>