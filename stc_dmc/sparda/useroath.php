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
			SELECT * FROM `stc_own_agents` WHERE `stc_own_agents_userid`='".$user."' AND `stc_own_agents_password`='".$pass."'
		");
		if(mysqli_num_rows($checkbumblebee)>0){
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$user_name=$user_details['stc_own_agents_name'];
			$user_id=$user_details['stc_own_agents_id'];
			// setcookie("agentsperforme", $user_id, time() +3600000);
			// setcookie("agentsperformename", $user_name, time() + 3600000);
			$_SESSION['stc_agent_id']=$user_id;
			$_SESSION['stc_agent_name']=$user_name;
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
		$objlogin=new dante();
		$opobjlogin=$objlogin->stc_login($user,$pass);
		echo $opobjlogin;
	}
}

?>