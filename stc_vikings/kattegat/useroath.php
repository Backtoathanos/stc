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
		$uid = (int)$_SESSION['stc_empl_id'];
		$old = array();
		$oldq = mysqli_query($this->stc_dbs, "SELECT * FROM `stc_user` WHERE `stc_user_id`='".$uid."' LIMIT 1");
		if($oldq && mysqli_num_rows($oldq) > 0){
			$old = mysqli_fetch_assoc($oldq);
		}
		$pass_sql = '';
		if(trim((string)$userpass1) !== ''){
			$pass_sql = ", `stc_user_password`='".mysqli_real_escape_string($this->stc_dbs, $userpass1)."'";
		}
		$updateloki=mysqli_query($this->stc_dbs, "
			UPDATE `stc_user` SET 
				`stc_user_name`='".mysqli_real_escape_string($this->stc_dbs, $username)."',
				`stc_user_address`='".mysqli_real_escape_string($this->stc_dbs, $useraddress)."',
				`stc_user_phone`='".mysqli_real_escape_string($this->stc_dbs, $userphone)."',
				`stc_user_phone_again`='".mysqli_real_escape_string($this->stc_dbs, $userwhats)."',
				`stc_user_email`='".mysqli_real_escape_string($this->stc_dbs, $useremail)."',
				`stc_user_userid`='".mysqli_real_escape_string($this->stc_dbs, $userid)."'
				".$pass_sql."
			WHERE `stc_user_id`='".$uid."'
		");
		if($updateloki){
			$this->touch_user_log($old, array(
				'name' => $username,
				'address' => $useraddress,
				'phone' => $userphone,
				'whatsapp' => $userwhats,
				'email' => $useremail,
				'userid' => $userid,
				'password' => $userpass1,
			));
			$odin="Updated!!!";
		}else{
			$odin= "You can not do this!!!";
		}
		return $odin;
	}

	private function touch_user_log($old, $new){
		$uid = (int)$_SESSION['stc_empl_id'];
		if($uid < 1){
			return;
		}
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$ipEsc = mysqli_real_escape_string($this->stc_dbs, $ip);
		$today = date('Y-m-d');
		$fields = array(
			'name' => array('user created as ', 'stc_user_name'),
			'address' => array('user address ', 'stc_user_address'),
			'phone' => array('user phone ', 'stc_user_phone'),
			'whatsapp' => array('user whatsapp ', 'stc_user_phone_again'),
			'email' => array('user email ', 'stc_user_email'),
			'userid' => array('user id ', 'stc_user_userid'),
		);
		foreach($fields as $key => $meta){
			$after = trim((string)($new[$key] ?? ''));
			$before = trim((string)($old[$meta[1]] ?? ''));
			if($after === '' || $after === $before){
				continue;
			}
			$this->insert_user_log($uid, $meta[0].$after, $today, $ipEsc);
		}
		$newpass = trim((string)($new['password'] ?? ''));
		$oldpass = trim((string)($old['stc_user_password'] ?? ''));
		if($newpass !== '' && $newpass !== $oldpass){
			$from = $oldpass !== '' ? $oldpass : '123456';
			$this->insert_user_log($uid, 'updated password from '.$from.' to '.$newpass, $today, $ipEsc);
		}
	}

	private function insert_user_log($uid, $log, $today, $ipEsc){
		$logEsc = mysqli_real_escape_string($this->stc_dbs, substr((string)$log, 0, 255));
		mysqli_query($this->stc_dbs, "
			INSERT INTO `user_log`
				(`user_type`, `user_id`, `log`, `date`, `status`, `created_by`, `updated_by`, `updated_date`, `ip_address`)
			VALUES
				('vikings', '".$uid."', '".$logEsc."', '".$today."', 1, '".$uid."', '".$uid."', '".$today."', '".$ipEsc."')
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
	$username=trim((string)($_POST['uname'] ?? ''));
	$useraddress=trim((string)($_POST['uaddress'] ?? ''));
	$userphone=trim((string)($_POST['uphone'] ?? ''));
	$userwhats=trim((string)($_POST['uwhats'] ?? ''));
	$useremail=trim((string)($_POST['uemail'] ?? ''));
	$userid=trim((string)($_POST['uid'] ?? ''));
	$userpass1=trim((string)($_POST['upassword'] ?? ''));
	$userpass2=trim((string)($_POST['upasswordagain'] ?? ''));
	$opobobjcalluser='';
	if($username==='' || $useraddress==='' || $userphone==='' || $userwhats==='' || $useremail==='' || $userid===''){
		$opobobjcalluser='Dont let below fields empty!!!';
	}elseif($userpass1!==$userpass2){
		$opobobjcalluser='Password does not match.';
	}else{
		$objcalluser=new prime();
		$opobobjcalluser=$objcalluser->stc_update_user($username, $useraddress, $userphone, $userwhats, $useremail, $userid, $userpass1);
	}
	echo $opobobjcalluser;
}
?>