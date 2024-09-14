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
class dante extends tesseract{	

	// check agents existance
	public function stc_login($user,$pass){
		$checkbumblebee=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_trading_user` 
			WHERE (`stc_trading_user_cont`='".$user."'
				OR `stc_trading_user_email`='".$user."'
			) 
			AND `stc_trading_user_password`='".$pass."'
		");
		if(mysqli_num_rows($checkbumblebee)>0){
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$op="success";
		}else{
			$op="Please Check Username & Password Again.";
		}
		return $op;
	}
}

if(isset($edata['check_login'])){
	$user=$edata['user'];
	$pass=$edata['password'];
	$result='';
	if(empty($user) || empty($pass)){
	  	$result = "Both Fields are required";
	}else{		
		$objlogin=new dante();
		$result=$objlogin->stc_login($user,$pass);
	}
	$response[] = array("result" => $result);
	echo json_encode($response);
}

?>