<?php

require_once __DIR__."/Database.php";
require_once __DIR__."/Functions.php";

class Reinigung {

	const OFFEN = 0;
	const ZUGEWIESEN = 1;
	const ERLEDIGT = 2;


	public function postCreate($timestamp, $name) {
		$db = connectToDB();
		$db->query("INSERT INTO `android_kugler`.`coffee_reinigungen` (`termin`, `name`, `status`,`timestamp`) VALUES ($timestamp, $name, self::OFFEN);");
		
		return "Reinigung eingetragen";

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
	}




}
    

   ?>