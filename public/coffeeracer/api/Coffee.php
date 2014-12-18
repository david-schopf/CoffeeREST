<?php

require_once __DIR__."/Database.php";
require_once __DIR__."/Functions.php";

class Coffee {

	const SMALL = 1;
   const LARGE = 2;
   const PRICE_SMALL = 60;
   const PRICE_LARGE = 80;

	public function buy($num_small, $num_large, ) {

		connectToDB();
		// Small coffees
		for ($i = 0; $i<num_small; $i++) {
			  $query = "INSERT INTO `android_kugler`.`coffee_coffees` (`preis`,`typ`, `timestamp`) VALUES (".self::PRICE_SMALL.",". self::SMALL.",".time().")" );
		}

		// Large coffees 
		  for (ji = 0; $j<num_small; $j++) {
			  $query = "INSERT INTO `android_kugler`.`coffee_coffees` (`preis`,`typ`, `timestamp`) VALUES (".self::PRICE_LARGE.",". self::LARGE.",".time().")" );
		}

	}

	function prices() {
		return array ("small" => self.PRICE_SMALL, "large" => self.PRICE_LARGE);
	}


}
    

   ?>