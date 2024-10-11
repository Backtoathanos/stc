<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
include "../../MCU/obdb.php";
$edata=json_decode(file_get_contents("php://input"), true);
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Agents ----------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class dante extends tesseract {	

	// Check agent's existence
	public function stc_login($user, $pass) {
		$checkbumblebee = mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_trading_user` 
			WHERE (`stc_trading_user_cont` = '".$user."'
				OR `stc_trading_user_email` = '".$user."'
			) 
			AND `stc_trading_user_password` = '".$pass."'
		");

		if (mysqli_num_rows($checkbumblebee) > 0) {
			$user_details = mysqli_fetch_assoc($checkbumblebee);
			$op = "success";
			$user_id = $user_details['stc_trading_user_id']; // Fetch user ID
		} else {
			$op = "Please Check Username & Password Again.";
			$user_id = null; // Set to null in case of failure
		}

		// Return both result and user ID
		return array(
			"result" => $op,
			"user_id" => $user_id
		);
	}
}

if (isset($edata['check_login'])) {
	$user = $edata['user'];
	$pass = $edata['password'];
	$result = '';

	if (empty($user) || empty($pass)) {
	  	$result = array("result" => "Both Fields are required");
	} else {		
		$objlogin = new dante();
		$result = $objlogin->stc_login($user, $pass);
	}

	$response[] = $result;
	echo json_encode($response);
}


?>