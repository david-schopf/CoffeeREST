<?php

require_once __DIR__."/Database.php";
require_once __DIR__."/Functions.php";

class Coffee {

	const SMALL = 1;
   const LARGE = 2;
   const PRICE_SMALL = 60;
   const PRICE_LARGE = 80;

	public function postBuy($userID, $num_small=1, $num_large=0) {

	
		$db = connectToDB();
		// Small coffees
		for ($i = 0; $i<$num_small; $i++) {
			  $query = "INSERT INTO `android_kugler`.`coffee_coffees` (`preis`,`typ`, `timestamp`, `userID`) VALUES (".self::PRICE_SMALL.",". self::SMALL.",".time().",".$userID.")" ;
			  $db->query($query);
			 
		}

		// Large coffees 
		  for ($j = 0; $j<$num_large; $j++) {
			  $query = "INSERT INTO `android_kugler`.`coffee_coffees` (`preis`,`typ`, `timestamp`, `userID`) VALUES (".self::PRICE_LARGE.",". self::LARGE.",".time().",".$userID.")" ;
		}

		
		return array("coffee" => "replace_with_coffee_json_object");

	}

	function prices() {
		return array ("small" => self.PRICE_SMALL, "large" => self.PRICE_LARGE);
	}


}
    

   ?>