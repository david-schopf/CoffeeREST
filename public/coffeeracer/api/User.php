<?php


/**
 * @access protected
 */
class User
{

    public function users($userID)
    {
        $db = connectToDB();

        $result = $db->query("SElECT u.id, user_name, display_name, email, coffee_count, guthaben, sign_up_stamp,  IF(ISNULL(b.buddyID),0,1) AS isbuddy FROM coffee_user_users AS u
	LEFT JOIN coffee_buddies AS b ON (u.id = b.userID AND userID={$userID}) GROUP BY u.id");

        return resultToJSON($result);
    }

    /**
     * Returns data for a single user
     *
     * @param $userID
     * @param $profilID
     * @return array|Object
     */
    public function data($userID, $profilID)
    {
        $db = connectToDB();
        $query = "SElECT u.id, user_name, display_name, email, coffee_count, guthaben, sign_up_stamp, IF(ISNULL(b.userID),0,1) AS isbuddy FROM coffee_user_users AS u
	LEFT JOIN coffee_buddies AS b ON (u.id = b.buddyID AND userID={$userID})  WHERE u.id={$profilID} ";
        $result = $db->query($query);

        return resultToJSON($result, true);
    }

    public function coffees($userID)
    {
        $db = connectToDB();

        $result = $db->query("SELECT id, preis, timestamp, userID FROM coffee_coffees WHERE userID=" . $userID);

        return resultToJSON($result);
    }

    public function reinigungen($userID)
    {
        $db = connectToDB();
        $query = "SELECT `id`, `termin`, `name`, `status` FROM `android_kugler`.`coffee_reinigungen` WHERE  `id`=$userID;";
        $result = $db->query($query);

        return resultToJSON($result);
    }

    public function aufladen($userID, $betrag, $code)
    {

        $betrag = $betrag * 100;

        $db = connectToDB();
        $usernameQ = "SELECT display_name FROM coffee_user_users WHERE id={$userID} LIMIT  1";
        $username = resultToJSON($db->query($usernameQ), true)['display_name'];
        // Nur ein Code pro Person aktiv
        $db->query("DELETE FROM `android_kugler`.`coffee_aufladungen` WHERE `userID`={$userID} AND `verified`=0");

        // Alte Codes überschreiben
        $db->query("UPDATE `android_kugler`.`coffee_aufladungen` SET `code`='XXXX' WHERE `userID`={$userID} AND `verified`=1 AND `code`='{$code}'");

        $saved = $db->query("INSERT INTO `android_kugler`.`coffee_aufladungen` (`userID`, `betrag`, `timestamp`, `code`, `verified`) VALUES ($userID, $betrag, " . time() . ", $code, 0); ");

        if ($saved) {
            $mail_responses = sendCodeEmail($username, $code, $betrag / 100);
            return array("success" => $code, "email" => $mail_responses[0]);
        } else
            return array("error" => "Code nicht gespeichert");
    }

    public function verifyCode($userID, $code)
    {

        $db = connectToDB();
        $query = "SELECT `id`,`verified` FROM `coffee_aufladungen` WHERE `userID`=" . $userID . " AND `code`='" . $code . "'";
        $result = $db->query($query);
        if ($result != null) {
            $row = $result->fetch_assoc();
            $id = $row['id'];
            $status = $row['verified'];
        }
        // Kein Code gefunden
        if (!$id) {
            return array("error" => "Code ist falsch");
        }

        if ($status == 1) {
            return array("error" => "Code bereits verwendet");
        }

        // Code verify
        $verifyQuery = "UPDATE `android_kugler`.`coffee_aufladungen` SET `verified` = '1' WHERE `coffee_aufladungen`.`id` = {$id}";
        $db->query($verifyQuery);

        // Update guthaben in user table
        $newGuthaben = updateGuthaben($userID);

        return array(
            "guthaben" => $newGuthaben
        );
    }

    public function aufladungen($userID)
    {
        $db = connectToDB();
        $query = "SELECT `id`,`userID`,`betrag`,`timestamp`,`code` FROM `coffee_aufladungen` WHERE `userID`=" . $userID . " AND `verified`=1";
        $result = $db->query($query);
        return resultToJSON($result);
    }

    public function addBuddy($userID, $buddyID)
    {
        $db = connectToDB();

        $db->query("INSERT INTO `android_kugler`.`coffee_buddies` (`userID`, `buddyID`, `timestamp`) VALUES ({$userID}, {$buddyID}, " . time() . ");");
        return array(
            "success" => "created"
        );
    }

    public function removeBuddy($userID, $buddyID)
    {
        $db = connectToDB();

        $db->query("DELETE FROM `android_kugler`.`coffee_buddies` WHERE `userID`={$userID} AND `buddyID`={$buddyID}");
        return array(
            "success" => "removed"
        );
    }

    public function buddies($userID)
    {
        $db = connectToDB();
        $query = "SELECT u.id, user_name, display_name, email, coffee_count, guthaben, sign_up_stamp, 1 AS isbuddy  FROM coffee_user_users AS u INNER JOIN `coffee_buddies` AS b ON u.id=b.buddyID WHERE userID={$userID}";

        $result = $db->query($query);

        return resultToJSON($result);
    }

    /*
     * Get the logged in user: Entweder durch sername oder userID
     */
    public function loggedin($userDescriptor)
    {
        $db = connectToDB();
        $userID = intval($userDescriptor);

        $query = "SELECT id, user_name, display_name, email, coffee_count, guthaben, sign_up_stamp, 0 AS isbuddy  FROM coffee_user_users WHERE `user_name`='" . $userDescriptor . "' OR `email`='" . $userDescriptor . "' OR `id`=" . $userID . " LIMIT 1";

        $result = $db->query($query);

        return resultToJSON($result, true);
    }

    public function history($userID)
    {
        $db = connectToDB();

        $nameQuery = "SELECT `display_name` FROM coffee_user_users WHERE id=" . $userID . " LIMIT 1";
        $result = $db->query($nameQuery);
        $row = $result->fetch_assoc();
        $displayname = $row ['display_name'];

        $coffeesQ = "SELECT `id`,`preis`,`typ`, `timestamp`, `userID` FROM `android_kugler`.`coffee_coffees` WHERE `userID`={$userID} ORDER BY `timestamp` DESC";
        $aufladungenQ = "SELECT `id`,`userID`,`betrag`,`timestamp`,`code` FROM `coffee_aufladungen` WHERE `userID`=" . $userID . " AND `verified`=1";
        $reinigungenQ = "SELECT `id`, `termin`, `name`, `status`,`timestamp` FROM `android_kugler`.`coffee_reinigungen` WHERE  `name`='" . $displayname . "'";

        $coffees = resultToJSON($db->query($coffeesQ));
        $aufladungen = resultToJSON($db->query($aufladungenQ));
        $reinigungen = resultToJSON($db->query($reinigungenQ));

        return array(
            "coffees" => $coffees,
            "aufladungen" => $aufladungen,
            "reinigungen" => $reinigungen
        );
    }
}