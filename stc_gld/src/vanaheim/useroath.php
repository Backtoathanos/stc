<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
// Allow from any origin
header("Access-Control-Allow-Origin: *");
// Allow specific HTTP methods
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// Allow specific headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");
include "../../../MCU/obdb.php";
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
			$user_name=$user_details['stc_trading_user_name'];
			$user_id=$user_details['stc_trading_user_id'];
			$op="success";
		}else{
			$op="Please Check Username & Password Again.";
		}
		return $op;
	}
}

if(isset($_POST['agent_signin'])){
	$user=$_POST['stc_electro_login_uname'];
	$pass=$_POST['stc_electro_login_upassword'];
	if(empty($user) || empty($pass)){
	  	$message = "Both Fields are required";
	}else{		
		$objlogin=new dante();
		$opobjlogin=$objlogin->stc_login($user,$pass);
		echo $opobjlogin;
	}
}

?>