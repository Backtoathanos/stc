<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Agents ----------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class prime extends tesseract{	

	// check agents existance
	public function stc_login($user,$pass){
		$checkbumblebee=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_agents` 
			WHERE `stc_agents_userid`='".mysqli_real_escape_string($this->stc_dbs, $user)."' 
			AND `stc_agents_pass`='".mysqli_real_escape_string($this->stc_dbs, $pass)."'
		");
		if(mysqli_num_rows($checkbumblebee)>0){
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$user_name=$user_details['stc_agents_name'];
			$user_id=$user_details['stc_agents_id'];
			$_SESSION['stc_agent_id']=$user_id;
			$_SESSION['stc_agent_name']=$user_name;
			$_SESSION['stc_agent_role']=$user_details['stc_agents_role'];
			$op="success";
		}else{
			$op="Please Check Username & Password Again.";
		}
		return $op;
	}

	public function stc_update_password($stc_ac_id, $stc_ac_pass, $stc_ac_repass){
		$op='';
		$updatebumblebee=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_agents`
			SET
			    `stc_agents_pass` = '".mysqli_real_escape_string($this->stc_dbs, $stc_ac_pass)."'
			WHERE
				`stc_agents_id` = '".mysqli_real_escape_string($this->stc_dbs, $stc_ac_id)."'
		");
		if($updatebumblebee){
			$op="success";
		}else{
			$op="error";
		}
		return $op;
	}
}

if(isset($_POST['agent_signin'])){
	if(empty($_POST["agent_name"]) || empty($_POST["agent_pass"])){
	  	$message = "Both Fields are required";
	}else{
		$user=$_POST['agent_name'];
		$pass=$_POST['agent_pass'];
		$objlogin=new prime();
		$opobjlogin=$objlogin->stc_login($user,$pass);
		echo $opobjlogin;
	}
}

if(isset($_POST['stc_ag_account_update'])){
	$out="";
	if(empty($_SESSION['stc_agent_id'])){
	  	$out = "Both Fields are required";
	}else{
		$stc_ac_id=$_POST['stc_ac_id'];
		$stc_ac_pass=$_POST['stc_ac_pass'];
		$stc_ac_repass=$_POST['stc_ac_repass'];
		$objlogin=new prime();
		$out=$objlogin->stc_update_password($stc_ac_id, $stc_ac_pass, $stc_ac_repass);
	}

	echo $out;
}

?>