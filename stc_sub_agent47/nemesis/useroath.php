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
			SELECT `stc_cust_pro_supervisor_id`, `stc_cust_pro_supervisor_fullname` 
			FROM `stc_cust_pro_supervisor` 
			WHERE (
				`stc_cust_pro_supervisor_contact`='".$user."' OR 
				`stc_cust_pro_supervisor_whatsapp`='".$user."' OR 
				`stc_cust_pro_supervisor_email`='".$user."'
			) AND `stc_cust_pro_supervisor_password`='".$pass."'
		");
		if(mysqli_num_rows($checkbumblebee)>0){
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$user_name=$user_details['stc_cust_pro_supervisor_fullname'];
			$user_id=$user_details['stc_cust_pro_supervisor_id'];
			// setcookie("agentsperforme", $user_id, time() +3600000);
			// setcookie("agentsperformename", $user_name, time() + 3600000);
			$_SESSION['stc_agent_sub_id']=$user_id;
			$_SESSION['stc_agent_sub_name']=$user_name;
			$op="success";
		}else{
			$op="Please Check Username & Password Again.";
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

?>