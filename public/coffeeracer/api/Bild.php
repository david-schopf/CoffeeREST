<?php

use Luracast\Restler\User;
require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/Functions.php";

class Bild {
	
	public function postUpload($userID) {

		/* Get file and save it */
		$bild = $_FILES['bild'];		
		$tempname = $bild['tmp_name'];
		$name = $bild['NAME'];
		$mime = $bild['type'];
				
		$ext = ".jpg";
		$filename = md5($tempname).$ext;
		$path = $path = str_replace("api", "images", __DIR__);
		$isMoved = move_uploaded_file($tempname, $path."\\".$filename);
		
		/*
		 * Put it into the database
		 */

		$userID = intval($userID);
		if ($userID > 0 == false)
			return array("error" => "UserID stimmt nicht");
			$db = connectToDB();
			$imageQuery = "UPDATE `android_kugler`.`coffee_user_users` SET `userimage` = '{$filename}' WHERE id = {$userID}";
			$saved = $db->query($imageQuery);
		return array("moved" => $isMoved, "path" => $path."\\".$filename, "database" => $saved);
	}

/**
 * Download images
 *
 * one could get the last update file
 *
 * @status 201
 * @return file
 */
	public function download($userID) {
		
		$path = str_replace("api", "images", __DIR__);
		
		$imageQuery = "SELECT `userimage` FROM `coffee_user_users` WHERE `id` = {$userID}";
		$db = connectToDB();
		$result = $db->query($imageQuery);
		
		$row = $result->fetch_assoc();
		$filename = $row['userimage'];
		
		if (strlen($filename) < "6") {
			return array("error" => "No profile picture set for user {$userID}");
		}
						
		$image = imagecreatefromjpeg($path."\\".$filename);
		
		// return $path."\\".$filename;
			
		// Die Content-Type-Kopfzeile senden, in diesem Fall image/jpeg
		header('Content-Type: image/jpeg');
		
		// Das Bild ausgeben
		imagejpeg($image);
		// Den Speicher freigeben
		imagedestroy($image);
		exit;
		
		// Den Speicher freigeben
		//imagedestroy($image);		
		
		//return resultToJSON($result);		
	}	
}