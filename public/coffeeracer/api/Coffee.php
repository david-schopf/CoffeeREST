<?php

require_once __DIR__."/Database.php";
require_once __DIR__."/Functions.php";


class Coffee {
	
	const SMALL =1;
	const LARGE = 2;

	public function postBuy($userID, $num_small, $price_small, $num_large, $price_large) {
	
		$db = connectToDB();
		// Small coffees
		for ($i = 0; $i<$num_small; $i++) {
			  $query = "INSERT INTO `android_kugler`.`coffee_coffees` (`preis`,`typ`, `timestamp`, `userID`) VALUES (".$price_small.",". self::SMALL.",".time().",".$userID.")" ;
			  $db->query($query);
			  $ids[] = $db->insert_id;  			  			   
		}

		// Large coffees 
		  for ($j = 0; $j<$num_large; $j++) {
			  $query = "INSERT INTO `android_kugler`.`coffee_coffees` (`preis`,`typ`, `timestamp`, `userID`) VALUES (".$price_large.",". self::LARGE.",".time().",".$userID.")" ;
			  $db->query($query);
			  $ids[] = $db->insert_id;			  	
		}
		
		$idString = implode(",", $ids);
		
		$coffeesQ = "SELECT `id`,`preis`,`typ`, `timestamp`, `userID` FROM `android_kugler`.`coffee_coffees` WHERE id IN ({$idString})  ORDER BY `timestamp` DESC";
		$coffees = $db->query($coffeesQ);
		
		$coffeeObjects= resultToJSON($coffees);
		
		// Update guthaben in user table
		$newGuthaben = updateGuthaben($userID);
		
		// Update coffee_count
		$coffeeCount = $num_large + $num_small;
		$countUpdateQuery = "UPDATE `android_kugler`.`coffee_user_users` SET `coffee_count`=`coffee_count`+{$coffeeCount} WHERE id={$userID}";	
		$db->query($countUpdateQuery);

		return array("coffees" => $coffeeObjects,
					"guthaben" => $newGuthaben );

	}
	
	public function coffees($userID) {
		$db =connectToDB();
		$query = "SELECT `id`,`preis`,`typ`, `timestamp`, `userID` FROM `android_kugler`.`coffee_coffees` WHERE `userID`={$userID} ORDER BY `timestamp` DESC";
		$result = $db->query($query);
		return resultToJSON($result);		
	}
	
	
	
}
    

   ?>