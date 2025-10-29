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
			$odin="Updated!!!";
		}else{
			$odin= "You can not do this!!!";
		}
		return $odin;
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