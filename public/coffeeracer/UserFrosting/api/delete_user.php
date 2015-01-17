<?php
/*

UserFrosting Version: 0.2.1 (beta)
By Alex Weissman
Copyright (c) 2014

Based on the UserCake user management system, v2.0.2.
Copyright (c) 2009-2012

UserFrosting, like UserCake, is 100% free and open-source.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the 'Software'), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

include('../models/db-settings.php');
include('../models/config.php');

set_error_handler('logAllErrors');

// User must be logged in
if (!isUserLoggedIn()){
  addAlert("danger", "You must be logged in to access this resource.");
  echo json_encode(array("errors" => 1, "successes" => 0));
  exit();
}

// POST Parameters: user_id
$validator = new Validator();
$user_id = $validator->requiredPostVar('user_id');

// Add alerts for any failed input validation
foreach ($validator->errors as $error){
  addAlert("danger", $error);
}

// Cannot delete master account
if ($user_id == $master_account){
	addAlert("danger", lang("ACCOUNT_DELETE_MASTER"));
	echo json_encode(array("errors" => 1, "successes" => 0));
	exit();	
} else {
	// Delete the user entirely.  This action cannot be undone!
	if (deleteUser($user_id)) {
		addAlert("success", lang("ACCOUNT_DELETIONS_SUCCESSFUL", array('1')));
	}
	else {
		echo json_encode(array("errors" => 1, "successes" => 0));
		exit();
	}
}

restore_error_handler();

// Allows for functioning in either ajax mode or graceful degradation to PHP/HTML only  
if (isset($_POST['ajaxMode']) and $_POST['ajaxMode'] == "true" ){
  echo json_encode(array("errors" => 0, "successes" => 1));
} else {
  header('Location: ' . getReferralPage());
  exit();
}

?>
