<?php

require_once __DIR__."/Database.php";
require_once __DIR__."/Functions.php";

/**
 * @access protected
 */
class Reinigung {

	const OFFEN = 0;
	const ZUGEWIESEN = 1;
	const ERLEDIGT = 2;


	public function create($termin, $username) {
		$db = connectToDB();
		
		// Check if termin already exists
		$reinigungsQ = "SELECT `id`, `termin`, `name`, `status`,`timestamp` FROM `android_kugler`.`coffee_reinigungen` WHERE `termin`={$termin} AND status=1;";
		$result = $db->query($reinigungsQ);
		if (is_array($result->fetch_assoc())) {
			return array("duplicate" => "termin already exists");
		}
		
		$query = "INSERT INTO `android_kugler`.`coffee_reinigungen` (`termin`, `name`, `status`, `timestamp`) VALUES ({$termin}, '{$username}', '".self::ZUGEWIESEN."', ".time().")";
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
	
	/**
	 * 
	 */
	function history($from) {
		
		$db = connectToDB();
		$query = "SELECT `id`, `termin`, `name`, `status`,`timestamp` FROM coffee_reinigungen WHERE termin > (".intval($from)." - 100 ) ORDER BY termin DESC";
		
		$result = $db->query($query);
		return resultToJSON($result);
	}




}
    

   ?>