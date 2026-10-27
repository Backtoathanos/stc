<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
require_once 'auth_helper.php';
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For employees ----------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class prime extends tesseract{	

	// check employees existance
	public function stc_emplyees_login($user,$pass){
		$checkbumblebee=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_user_id`,
				`stc_user_name`,
			    `stc_user_role`
			FROM `stc_user` 
			WHERE (`stc_user_userid`='".mysqli_real_escape_string($this->stc_dbs, $user)."' 
			OR `stc_user_phone`='".mysqli_real_escape_string($this->stc_dbs, $user)."'
			OR `stc_user_email`='".mysqli_real_escape_string($this->stc_dbs, $user)."')
			AND `stc_user_password`='".mysqli_real_escape_string($this->stc_dbs, $pass)."'
		");
		if(mysqli_num_rows($checkbumblebee)>0){
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$user_name=$user_details['stc_user_name'];
			$user_id=$user_details['stc_user_id'];
			$_SESSION['stc_empl_id']=$user_id;
			$_SESSION['stc_empl_name']=$user_name;
			$_SESSION['stc_empl_role']=$user_details['stc_user_role'];
			
			// Store authentication data in cookie for 30 days
			STCAuthHelper::storeAuthInCookie();
			
			$op="success";
		}else{
			$op="Please Check Username & Password Again.";
		}
		return $op;
	}

	public function stc_call_user(){
		$odin='';
		$checkloki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_user` WHERE `stc_user_id`='".$_SESSION['stc_empl_id']."'");
		$odin=mysqli_fetch_assoc($checkloki);
		return $odin;
	}

	public function stc_update_user( $username, $useraddress, $userphone, $userwhats, $useremail, $userid, $userpass1){
		$odin='';
		$updateloki=mysqli_query($this->stc_dbs, "
			UPDATE `stc_user` SET 
				`stc_user_name`='".$username."',
				`stc_user_address`='".$useraddress."',
				`stc_user_phone`='".$userphone."',
				`stc_user_phone_again`='".$userwhats."',
				`stc_user_email`='".$useremail."',
				`stc_user_userid`='".$userid."',
				`stc_user_password`='".$userpass1."'
			WHERE `stc_user_id`='".$_SESSION['stc_empl_id']."'
		");
		if($updateloki){
			$this->touch_user_log($username, $useraddress, $userpass1);
			$odin="Updated!!!";
		}else{
			$odin= "You can not do this!!!";
		}
		return $odin;
	}

	private function touch_user_log($username, $useraddress, $userpass){
		$uid = (int) $_SESSION['stc_empl_id'];
		if($uid < 1){
			return;
		}
		$table = mysqli_query($this->stc_dbs, "SHOW TABLES LIKE 'user_log'");
		if(!$table || mysqli_num_rows($table) === 0){
			return;
		}
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$ipEsc = mysqli_real_escape_string($this->stc_dbs, $ip);
		$today = date('Y-m-d');
		mysqli_query($this->stc_dbs, "
			UPDATE `user_log`
			SET `updated_by`='".$uid."',
				`updated_date`='".$today."',
				`ip_address`='".$ipEsc."'
			WHERE `user_type`='vikings' AND `user_id`='".$uid."'
		");
		$this->insert_user_log_if_missing($uid, 'user created as %', 'user created as '.$username, $today, $ipEsc);
		if(trim((string) $useraddress) !== ''){
			$this->insert_user_log_if_missing($uid, 'user address %', 'user address '.$useraddress, $today, $ipEsc);
		}
		if(trim((string) $userpass) !== '' && $userpass !== '123456'){
			$this->insert_user_log_if_missing($uid, 'updated password from %', 'updated password from 123456 to '.$userpass, $today, $ipEsc);
		}
	}

	private function insert_user_log_if_missing($uid, $like, $log, $today, $ipEsc){
		$likeEsc = mysqli_real_escape_string($this->stc_dbs, $like);
		$exists = mysqli_query($this->stc_dbs, "
			SELECT `id` FROM `user_log`
			WHERE `user_type`='vikings' AND `user_id`='".$uid."' AND `log` LIKE '".$likeEsc."'
			LIMIT 1
		");
		if($exists && mysqli_num_rows($exists) > 0){
			return;
		}
		$logEsc = mysqli_real_escape_string($this->stc_dbs, $log);
		mysqli_query($this->stc_dbs, "
			INSERT INTO `user_log`
				(`user_type`, `user_id`, `log`, `date`, `status`, `created_by`, `updated_by`, `updated_date`, `ip_address`)
			VALUES
				('vikings', '".$uid."', '".$logEsc."', '".$today."', 1, 1, '".$uid."', '".$today."', '".$ipEsc."')
		");
	}
}

if(isset($_POST['employee_signin'])){
	if(empty($_POST["employee_name"]) || empty($_POST["employee_pass"])){
	  	$message = "Both Fields are required";
	}else{
		$user=$_POST['employee_name'];
		$pass=$_POST['employee_pass'];
		$objlogin=new prime();
		$opobjlogin=$objlogin->stc_emplyees_login($user,$pass);
		echo $opobjlogin;
	}
}


if(isset($_POST['load_u'])){
	$objcalluser=new prime();
	$opobobjcalluser=$objcalluser->stc_call_user();
	echo json_encode($opobobjcalluser);
}

if(isset($_POST['user_update'])){
	$username=$_POST['uname'];
	$useraddress=$_POST['uaddress'];
	$userphone=$_POST['uphone'];
	$userwhats=$_POST['uwhats'];
	$useremail=$_POST['uemail'];
	$userid=$_POST['uid'];
	$userpass1=$_POST['upassword'];
	$userpass2=$_POST['upasswordagain'];
	if(empty($username) || empty($useraddress) || empty($userphone) || empty($userwhats)|| empty($useremail) || empty($userid) || empty($userphone) || empty($userpass1) || empty($userpass2)){
		echo "Dont let below fields empty!!!";
	}elseif(!empty($userpass1) && !empty($userpass2) && $userpass1 != $userpass2){
		echo "Password doesnot match!!!";
	}else{
		$objcalluser=new prime();
		$opobobjcalluser=$objcalluser->stc_update_user( $username, $useraddress, $userphone, $userwhats, $useremail, $userid, $userpass1
		);
		
	}
	echo $opobobjcalluser;
}
?>