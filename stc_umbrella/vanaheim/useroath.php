<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Agents ----------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class dante extends tesseract{	

	// check agents existance
	public function stc_login($user,$pass){
		$checkbumblebee=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_school` 
			WHERE (`stc_school_user_fullName`='".$user."'
				OR `stc_school_user_email`='".$user."' 
				OR `stc_school_user_contact`='".$user."' 
			) 
			AND `stc_school_user_password`='".$pass."'
			AND `stc_school_user_status`='1'
		");
		if(mysqli_num_rows($checkbumblebee)>0){
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$user_name=$user_details['stc_school_user_fullName'];
			$user_id=$user_details['stc_school_user_id'];
			$user_for=$user_details['stc_school_user_for'];
			// setcookie("agentsperforme", $user_id, time() +3600000);
			// setcookie("agentsperformename", $user_name, time() + 3600000);
			$checkbumblebeeagain=mysqli_query($this->stc_dbs, "
				SELECT `stc_school_teacher_id` FROM `stc_school`
				LEFT JOIN `stc_school_teacher`
				ON `stc_school_teacher_userid`=`stc_school_user_id`
				WHERE `stc_school_user_id`='".mysqli_real_escape_string($this->stc_dbs, $user_id)."'
			");
			$teacher_id='';
			foreach($checkbumblebeeagain as $checkbumblebeeagainrow){
				$teacher_id=$checkbumblebeeagainrow['stc_school_teacher_id'];
			}
			$_SESSION['stc_school_user_id']=$user_id;
			$_SESSION['stc_school_user_name']=$user_name;
			$_SESSION['stc_school_user_for']=$user_for;
			$_SESSION['stc_school_teacher_id']=$teacher_id;
			$op="success";
		}else{
			$op="Please Check Username & Password Again.";
		}
		return $op;
	}
}

if(isset($_POST['agent_signin'])){
	$user=$_POST['stc_school_login_uname'];
	$pass=$_POST['stc_school_login_upassword'];
	if(empty($user) || empty($pass)){
	  	$message = "Both Fields are required";
	}else{		
		$objlogin=new dante();
		$opobjlogin=$objlogin->stc_login($user,$pass);
		echo $opobjlogin;
	}
}

?>