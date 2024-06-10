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
			SELECT `stc_cust_pro_supervisor_id`, `stc_cust_pro_supervisor_fullname`, `stc_cust_pro_supervisor_category` 
			FROM `stc_cust_pro_supervisor` 
			WHERE (
				`stc_cust_pro_supervisor_contact`='".mysqli_real_escape_string($this->stc_dbs, $user)."' OR 
				`stc_cust_pro_supervisor_whatsapp`='".mysqli_real_escape_string($this->stc_dbs, $user)."' OR 
				`stc_cust_pro_supervisor_email`='".mysqli_real_escape_string($this->stc_dbs, $user)."'
			) AND `stc_cust_pro_supervisor_password`='".mysqli_real_escape_string($this->stc_dbs, $pass)."'
			AND `stc_cust_pro_supervisor_status`=1
		");
		if(mysqli_num_rows($checkbumblebee)>0){
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$user_name=$user_details['stc_cust_pro_supervisor_fullname'];
			$user_id=$user_details['stc_cust_pro_supervisor_id'];
			$category=$user_details['stc_cust_pro_supervisor_category'];
			// setcookie("agentsperforme", $user_id, time() +3600000);
			// setcookie("agentsperformename", $user_name, time() + 3600000);
			$_SESSION['stc_agent_sub_id']=$user_id;
			$_SESSION['stc_agent_sub_name']=$user_name;
			$_SESSION['stc_agent_sub_category']=$category;
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
			    `stc_cust_pro_supervisor`
			SET
			    `stc_cust_pro_supervisor_password` = '".mysqli_real_escape_string($this->stc_dbs, $stc_ac_pass)."'
			WHERE
				`stc_cust_pro_supervisor_id` = '".mysqli_real_escape_string($this->stc_dbs, $stc_ac_id)."'
		");
		if($updatebumblebee){
			$op="success";
		}else{
			$op="error";
		}
		return $op;
	}

	public function stc_forgot_password($user){
		$op='';
		$user = mysqli_real_escape_string($this->stc_dbs, $user);
		$query = "SELECT `stc_cust_pro_supervisor_email` FROM `stc_cust_pro_supervisor` WHERE `stc_cust_pro_supervisor_contact` = '$user' OR `stc_cust_pro_supervisor_whatsapp` = '$user' OR `stc_cust_pro_supervisor_email` = '$user'";

		$result = mysqli_query($this->stc_dbs, $query);

		if ($result && mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$email = $row['stc_cust_pro_supervisor_email'];
			$token = bin2hex(random_bytes(50)); // Generate a unique token
			$updateQuery = " UPDATE `stc_cust_pro_supervisor` SET `stc_cust_pro_supervisor_token` = '$token' WHERE `stc_cust_pro_supervisor_contact` = '$user' OR `stc_cust_pro_supervisor_whatsapp` = '$user' OR `stc_cust_pro_supervisor_email` = '$user'";

			$updateResult = mysqli_query($this->stc_dbs, $updateQuery);
			$resetLink = "https://stcassociate.com/stc_sub_agent47/reset_password.php?token=" . $token;
			$subject = "Password Reset Request";
			$message = "Click the following link to reset your password: " . $resetLink;
			$headers = "From: info@stcassociate.com";

			if (mail($email, $subject, $message, $headers)) {
				$op = "success";
			} else {
				$op = "error";
			}
		} else {
			$op = "notfound";
		}

		return $op;
	}

	public function stc_reset_password($agent_id, $agent_pass){
		$op='';
		$query = "UPDATE `stc_cust_pro_supervisor` SET `stc_cust_pro_supervisor_password`='".mysqli_real_escape_string($this->stc_dbs, $agent_pass)."', `stc_cust_pro_supervisor_token`='' WHERE `stc_cust_pro_supervisor_id`='".mysqli_real_escape_string($this->stc_dbs, $agent_id)."'";
		$result = mysqli_query($this->stc_dbs, $query);
		if($result){
			$checkbumblebee=mysqli_query($this->stc_dbs, "SELECT `stc_cust_pro_supervisor_id`, `stc_cust_pro_supervisor_fullname`, `stc_cust_pro_supervisor_category` FROM `stc_cust_pro_supervisor` WHERE `stc_cust_pro_supervisor_id`='".mysqli_real_escape_string($this->stc_dbs, $agent_id)."'");
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$user_name=$user_details['stc_cust_pro_supervisor_fullname'];
			$user_id=$user_details['stc_cust_pro_supervisor_id'];
			$category=$user_details['stc_cust_pro_supervisor_category'];
			$_SESSION['stc_agent_sub_id']=$user_id;
			$_SESSION['stc_agent_sub_name']=$user_name;
			$_SESSION['stc_agent_sub_category']=$category;
			$op = "success";
		} else {
			$op = "error";
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
	if(empty($_SESSION['stc_agent_sub_id'])){
	  	$out = "empty";
	}else{
		$stc_ac_id=$_POST['stc_ac_id'];
		$stc_ac_pass=$_POST['stc_ac_pass'];
		$stc_ac_repass=$_POST['stc_ac_repass'];
		$objlogin=new prime();
		$out=$objlogin->stc_update_password($stc_ac_id, $stc_ac_pass, $stc_ac_repass);
	}

	echo $out;
}



if(isset($_POST['agent_forgotpassword'])){
	if(empty($_POST["agent_name"])){
	  	$message = "Both Fields are required";
	}else{
		$user=$_POST['agent_name'];
		$objlogin=new prime();
		$opobjlogin=$objlogin->stc_forgot_password($user);
		echo $opobjlogin;
	}
}

if(isset($_POST['agent_resetpassword'])){
	$agent_pass=$_POST["agent_pass"];
	$agent_pass1=$_POST["agent_pass1"];
	$agent_id=$_POST["agent_id"];
	if(empty($_POST["agent_pass"]) || empty($_POST["agent_pass1"])){
	  	$message = "required";
	}else if($_POST["agent_pass"] != $_POST["agent_pass1"]){
	  	$message = "unmatch";
	}else{
		$objlogin=new prime();
		$message=$objlogin->stc_reset_password($agent_id, $agent_pass);
	}
	echo $message;
}
?>