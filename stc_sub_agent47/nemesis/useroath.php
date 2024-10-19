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
			SELECT `stc_cust_pro_supervisor_id`, `stc_cust_pro_supervisor_fullname`, `stc_cust_pro_supervisor_category`, `stc_cust_pro_supervisor_image` 
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
			$_SESSION['stc_agent_sub_image']=$user_details['stc_cust_pro_supervisor_image'];
			$op="success";
		}else{
			$op="Please Check Username & Password Again.";
		}
		return $op;
	}

	public function stc_update_password($stc_ac_id, $stc_ac_pass, $stc_ac_repass, $stc_old_password) {
		$op = '';
	
		// Fetch the current password from the database
		$query = mysqli_query($this->stc_dbs, "
			SELECT `stc_cust_pro_supervisor_password`
			FROM `stc_cust_pro_supervisor`
			WHERE `stc_cust_pro_supervisor_id` = '".mysqli_real_escape_string($this->stc_dbs, $stc_ac_id)."'
			AND `stc_cust_pro_supervisor_password` = '".mysqli_real_escape_string($this->stc_dbs, $stc_old_password)."'
		");
		// Check if the old password matches the stored one
		if (mysqli_num_rows($query)>0) {
				
			// Update the password if old password is correct
			$updatebumblebee = mysqli_query($this->stc_dbs, "
				UPDATE `stc_cust_pro_supervisor`
				SET `stc_cust_pro_supervisor_password` = '".mysqli_real_escape_string($this->stc_dbs, $stc_ac_pass)."'
				WHERE `stc_cust_pro_supervisor_id` = '".mysqli_real_escape_string($this->stc_dbs, $stc_ac_id)."'
			");
				
			if ($updatebumblebee) {
				$op = "success";
			} else {
				$op = "error updating password";
			}
		} else {
			$op = "old password incorrect";
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

	public function stc_update_image($user_id, $file_name) {
		// Assume you have a database connection setup in your prime class
		$sql = "UPDATE stc_cust_pro_supervisor SET stc_cust_pro_supervisor_image = '".$file_name."' WHERE stc_cust_pro_supervisor_id = '".$user_id."'";
		return mysqli_query($this->stc_dbs, $sql);
	}

	public function stc_update_accountdetails($stc_ac_id, $uid, $address, $pincode, $city, $state){
		$op = '';
	
		// Fetch the current password from the database
		$query = mysqli_query($this->stc_dbs, "
			SELECT `stc_cust_pro_supervisor_uid`
			FROM `stc_cust_pro_supervisor`
			WHERE `stc_cust_pro_supervisor_id` <> '".mysqli_real_escape_string($this->stc_dbs, $stc_ac_id)."'
			AND `stc_cust_pro_supervisor_uid` = '".mysqli_real_escape_string($this->stc_dbs, $uid)."'
		");
		// Check if the old password matches the stored one
		if (mysqli_num_rows($query)==0) {
				
			// Update the password if old password is correct
			$updatebumblebee = mysqli_query($this->stc_dbs, "
				UPDATE `stc_cust_pro_supervisor` SET `stc_cust_pro_supervisor_uid` = '".mysqli_real_escape_string($this->stc_dbs, $uid)."', `stc_cust_pro_supervisor_address` = '".mysqli_real_escape_string($this->stc_dbs, $address)."', `stc_cust_pro_supervisor_pincode` = '".mysqli_real_escape_string($this->stc_dbs, $pincode)."', `stc_cust_pro_supervisor_cityid` = '".mysqli_real_escape_string($this->stc_dbs, $city)."', `stc_cust_pro_supervisor_state_id` = '".mysqli_real_escape_string($this->stc_dbs, $state)."' WHERE `stc_cust_pro_supervisor_id` = '".mysqli_real_escape_string($this->stc_dbs, $stc_ac_id)."'
			");
				
			if ($updatebumblebee) {
				$op = "success";
			} else {
				$op = "error updating profile";
			}
		} else {
			$op = "invalid uid.";
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
		$stc_old_password=$_POST['stc_old_password'];
		$objlogin=new prime();
		$out=$objlogin->stc_update_password($stc_ac_id, $stc_ac_pass, $stc_ac_repass, $stc_old_password);
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

if (isset($_POST['upload_image'])) {
    $avatar = $_POST["avatar"];
    $out = '';
    
    if (empty($_SESSION['stc_agent_sub_id'])) {
        $out = "empty";
    } else {
        // Extract the base64 image string
        $image_parts = explode(";base64,", $avatar);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1]; // e.g. png, jpg
        $image_base64 = base64_decode($image_parts[1]);

        // Define directory and filename
        $directory = '../assets/images/user_images/';
        $user_id = $_SESSION['stc_agent_sub_id']; // assuming user ID is stored in session
        $file_name = 'user_' . $user_id . '.' . $image_type;
        $file_path = $directory . $file_name;

        // Create the directory if it doesn't exist
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
		$_SESSION['stc_agent_sub_image']='assets/images/user_images/'.$file_name;
        // Save the image file
        file_put_contents($file_path, $image_base64);

        // Now update the user table with the image filename
        $objlogin = new prime();
        $update_result = $objlogin->stc_update_image($user_id, $file_name); // Pass user ID and image name to update

        if ($update_result) {
            $out = 'success';
        } else {
            $out = 'error_updating_image';
        }
    }

    echo $out;
}

if(isset($_POST['stc_ag_accountdetails_update'])){
	$out="";
	if(empty($_SESSION['stc_agent_sub_id'])){
	  	$out = "empty";
	}else{
		$stc_ac_id=$_POST['stc_ac_id'];
		$uid=$_POST['uid'];
		$address=$_POST['address'];
		$pincode=$_POST['pincode'];
		$city=$_POST['city'];
		$state=$_POST['state'];
		$objlogin=new prime();
		$out=$objlogin->stc_update_accountdetails($stc_ac_id, $uid, $address, $pincode, $city, $state);
	}

	echo $out;
}

?>