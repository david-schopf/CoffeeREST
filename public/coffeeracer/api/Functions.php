<?php

use Mailgun\Mailgun;
use Luracast\Restler\Data\Object;

/**
 * @param mysqli_result $result
 * @param bool $asObject
 * @return array|Object
 */
function resultToJSON($result, $asObject = false)
{

    $json = array();

    while ($data = $result->fetch_assoc()) {
        $json[] = $data;
    }

    if ($asObject == true) {
        $json = $json[0];
    }

    // Prevent errors when result is empty
    if ($json == array()) {
        $json = ($asObject) ? new Object() : array();
    }

    return $json;
}

/**
 * @return mysqli
 */
function connectToDB()
{
    $db_host = "localhost"; //Host address (most likely localhost)
    $db_name = "android_kugler"; //Name of Database
    $db_user = "android_kugler"; //Name of database user
    $db_pass = "6aAiX0XiuC"; //Password for database user

    $database = new Database($db_host, $db_user, $db_pass, $db_name);
    $db = $database->getConnection();
    return $db;
}

function updateGuthaben($userID)
{
    $db = connectToDB();

    $guthabenQuery = "SELECT SUM(betrag) FROM `coffee_aufladungen` WHERE `userID`={$userID} AND `verified`=1";
    $coffeeQuery = "SELECT SUM(preis) FROM `coffee_coffees` WHERE `userID`={$userID}";

    $guthaben = $db->query($guthabenQuery);
    $ausgaben = $db->query($coffeeQuery);

    $guthabenInt = $guthaben->fetch_row()[0];
    $ausgabenInt = $ausgaben->fetch_row()[0];

    $newGuthaben = $guthabenInt - $ausgabenInt;

    $db->query("UPDATE `android_kugler`.`coffee_user_users` SET `guthaben`={$newGuthaben} WHERE  `id`={$userID};");

    return $newGuthaben;
}


function sendCodeEmail($username, $code, $betrag)
{

    $recipientsQuery = "SELECT u.email FROM coffee_user_users AS u RIGHT JOIN
				coffee_user_user_group_matches AS m ON u.id=m.user_id RIGHT JOIN coffee_user_groups AS g
				 ON m.group_id=g.id WHERE g.name='KaffeeFee'";

    $db = connectToDB();
    $result = $db->query($recipientsQuery);
    $emails = array();
    while ($data = $result->fetch_row()) {
        $emails[] = $data[0];
    }

    # First, instantiate the SDK with your API credentials and define your domain.
    $mg = new Mailgun("key-44228b5b1ecc3e78e6f2a975508f7998");
    $domain = "pr-android.ftm.mw.tum.de";


# Now, compose and send your message.
    return $mg->sendMessage($domain, array('from' => 'lavidamokka@pr-android.ftm.mw.tum.de',
        'to' => 'lavidamokka@gmail.com',
        'subject' => "Aufladung Kaffee-Guthaben durch". $username,
        'text' => "Der Code für die Aufladung von ".$username." über ".$betrag." € lautet: ".$code));
	}

