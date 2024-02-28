<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
class login_check extends tesseract{
	public function stc_login($user,$pass){
		$qry=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_user` WHERE `stc_user_userid`='".$user."' AND `stc_user_password`='".$pass."'");
		if(mysqli_num_rows($qry)>0){
			$user_details=mysqli_fetch_assoc($qry);
			$user_name=$user_details['stc_user_name'];
			$user_id=$user_details['stc_user_id'];
			$_SESSION['stc_admin_info_id']=$user_id;
			$_SESSION['stc_admin_info_name']=$user_name;
			$op="success";
		}else{
			$op="Please Check Username & Password Again.";
		}
		return $op;
	}

	public function stc_call_user(){
		$odin='';
		$checkloki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_user` WHERE `stc_user_id`='".$_SESSION['stc_admin_info_id']."'");
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
			WHERE `stc_user_id`='".$_SESSION['stc_admin_info_id']."'
		");
		if($updateloki){
			$odin="Updated!!!";
		}else{
			$odin= "You can not do this!!!";
		}
		return $odin;
	}
}

class user_call extends tesseract{
	// filter user
	public function stc_search_user($search){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_user_id`,
				`stc_user_name`,
				`stc_user_address`,
				`stc_user_phone`,
				`stc_user_phone_again`,
				`stc_user_email`,
				`stc_user_userid`,
				`stc_user_password`,
				`stc_user_role`
			FROM `stc_user`
			WHERE `stc_user_name` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			ORDER BY `stc_user_name` ASC
		");
		$odin='
			<table class="table table-hover ">
	        	<thead>
	        	  <tr>
	        	    <th scope="col" align="centre">User Id</th>
	        	    <th scope="col" align="centre">User Name</th>
	        	    <th scope="col" align="centre" width="20%">User Address</th>
	        	    <th scope="col" align="centre">User Phone Number</th>
	        	    <th scope="col" align="centre">User Email</th>
	        	    <th scope="col" align="centre">User Uid</th>
	        	    <th scope="col" align="centre">User Password</th>  
	        	    <th scope="col" align="centre">User Role</th>                         
	        	    <th scope="col" align="centre">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td>No User Found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
					<tr>
						<td>'.$row['stc_user_id'].'</td>
						<td>'.$row['stc_user_name'].'</td>
						<td>'.$row['stc_user_address'].'</td>
						<td>'.$row['stc_user_phone'].'</td>
						<td>'.$row['stc_user_email'].'</td>
						<td>'.$row['stc_user_userid'].'</td>
						<td>'.$row['stc_user_password'].'</td>
						<td>'.$row['stc_user_role'].'</td>
						<td>
							<a 
								id="'.$row['stc_user_id'].'"
								class="stc-user-mail-it"
								href="#" 
								style="font-size:20px; color:black;padding:3px;float:center;"
							><i class="fa fa-envelope-open"></i>
							</a>
							<a 
								id="'.$row['stc_user_id'].'"
								class="stc-user-role-it"
								href="#" 
								style="font-size:20px; color:black;padding:3px;float:center;"
							><i class="fa fa-key"></i>
							</a>
						</td>
					</tr>
				';	
			}	
			$result=mysqli_fetch_assoc($check_loki);
					
		}
		$odin.='
				</tbody>
	       	</table>
		';
		return $odin;
	}

	// call user
	public function stc_search_user_call(){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_user_id`,
				`stc_user_name`,
				`stc_user_address`,
				`stc_user_phone`,
				`stc_user_phone_again`,
				`stc_user_email`,
				`stc_user_userid`,
				`stc_user_password`,
				`stc_user_role`
			FROM `stc_user`
			ORDER BY `stc_user_name` ASC
		");
		$odin='
			<table class="table table-hover ">
	        	<thead>
	        	  <tr>
	        	    <th scope="col" align="centre">User Id</th>
	        	    <th scope="col" align="centre">User Name</th>
	        	    <th scope="col" align="centre" width="20%">User Address</th>
	        	    <th scope="col" align="centre">User Phone Number</th>
	        	    <th scope="col" align="centre">User Email</th>
	        	    <th scope="col" align="centre">User Uid</th>
	        	    <th scope="col" align="centre">User Password</th>  
	        	    <th scope="col" align="centre">User Role</th>                         
	        	    <th scope="col" align="centre">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td>No User Found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
					<tr>
						<td>'.$row['stc_user_id'].'</td>
						<td>'.$row['stc_user_name'].'</td>
						<td>'.$row['stc_user_address'].'</td>
						<td>'.$row['stc_user_phone'].'</td>
						<td>'.$row['stc_user_email'].'</td>
						<td>'.$row['stc_user_userid'].'</td>
						<td>'.$row['stc_user_password'].'</td>
						<td>'.$row['stc_user_role'].'</td>
						<td>
							<a 
								id="'.$row['stc_user_id'].'"
								class="stc-user-mail-it"
								href="#" 
								style="font-size:20px; color:black;padding:3px;float:center;"
							><i class="fa fa-envelope-open"></i>
							</a>
							<a 
								id="'.$row['stc_user_id'].'"
								class="stc-user-role-it"
								href="#" 
								style="font-size:20px; color:black;padding:3px;float:center;"
							><i class="fa fa-key"></i>
							</a>
						</td>
					</tr>
				';	
			}	
			$result=mysqli_fetch_assoc($check_loki);
					
		}
		$odin.='
				</tbody>
	       	</table>
		';
		return $odin;
	}

	// save user
	public function stc_user_hit($user_name, $user_address, $user_phone_number, $user_whats_number, $user_email, $user_uid, $user_pass){
		$odin='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_user_id`
			FROM
				`stc_user`
			WHERE
				`stc_user_phone` = '".mysqli_real_escape_string($this->stc_dbs, $user_phone_number)."'
			OR 
				`stc_user_phone_again` = '".mysqli_real_escape_string($this->stc_dbs, $user_whats_number)."'
			OR 
				`stc_user_email` = '".mysqli_real_escape_string($this->stc_dbs, $user_email)."'
		");
		if(mysqli_num_rows($check_loki)==0){
			$set_loki=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_user`(
					`stc_user_name`,
					`stc_user_address`,
					`stc_user_phone`,
					`stc_user_phone_again`,
					`stc_user_email`,
					`stc_user_img`,
					`stc_user_userid`,
					`stc_user_password`,
					`stc_user_role`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $user_name)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_address)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_phone_number)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_whats_number)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_email)."',
					'NA',
					'".mysqli_real_escape_string($this->stc_dbs, $user_uid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_pass)."',
					'1'
				)
			");
			if($set_loki){
				$odin='User added succefully.';
			}else{
				$odin='Hmm!!! Something went wrong for adding a user.';
			}
		}else{
			$odin="This user is already exist.";
		}
		return $odin;
	}

	// role change
	public function stc_user_role($uid, $urole){
		$odin='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_user_id` 
			FROM 
				`stc_user` 
			WHERE 
				`stc_user_id` = '".mysqli_real_escape_string($this->stc_dbs, $uid)."' 
			AND 
				`stc_user_role` = '".mysqli_real_escape_string($this->stc_dbs, $urole)."' 
		");

		if(mysqli_num_rows($check_loki)>0){
			$odin="User is already assigned for that role.";
		}else{
			$set_loki=mysqli_query($this->stc_dbs, "
				UPDATE 
					`stc_user` 
				SET 
					`stc_user_role` = '".mysqli_real_escape_string($this->stc_dbs, $urole)."' 
				WHERE 
					`stc_user_id` = '".mysqli_real_escape_string($this->stc_dbs, $uid)."' 
			");
			if($set_loki){
				$odin="User assigned successfully.";
			}else{
				$odin="Hmm!!! Something went wrong on assigning the user.";
			}
		}
		return $odin;
	}

	// privilege save
	public function stc_user_privilege($user_id, $roles_val){
		$odin='';
		$odin_userrolecheck_qry=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_user_role` WHERE `stc_user_role_uid`='".mysqli_real_escape_string($this->stc_dbs, $user_id)."'
		");
		foreach($roles_val as $role_val_row){
			$odin_userroleset_qry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_user_role`(
				    `stc_user_role_uid`,
					`stc_user_role_privilege_id`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $user_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $role_val_row)."'
				)
			");
			if($odin_userroleset_qry){
				$odin="Role granted successfully.";
			}else{
				$odin="Hmm!!! Something went wrong on granting user.";
			}
		}
		return $odin;
	}

	// call privillage
	public function stc_user_privilege_get($uid){
		$odin=array();
		$odin_userrolecheck_qry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_user_role`
			WHERE `stc_user_role_uid`='".mysqli_real_escape_string($this->stc_dbs, $uid)."'
		");
		foreach($odin_userrolecheck_qry as $row){
			$odin[]=$row;
		}
		return $odin;
	}
}

class electronics_user extends tesseract{
	public function stc_search_euser($search){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_electronics_user_id`,
				`stc_electronics_user_fullName`,
				`stc_electronics_user_address`,
				`stc_electronics_user_email`,
				`stc_electronics_user_contact`,
				`stc_electronics_user_password`,
				`stc_electronics_user_for`
			FROM
				`stc_electronics_user`
			WHERE `stc_electronics_user_fullName` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			ORDER BY `stc_electronics_user_fullName` ASC
		");
		$odin='
			<table class="table table-hover ">
	        	<thead>
	        	  <tr>
	        	    <th scope="col" align="centre">User Id</th>
	        	    <th scope="col" align="centre">User Name</th>
	        	    <th scope="col" align="centre" width="20%">User Address</th>
	        	    <th scope="col" align="centre">User Phone Number</th>
	        	    <th scope="col" align="centre">User Email</th>
	        	    <th scope="col" align="centre">User Uid</th>
	        	    <th scope="col" align="centre">User Password</th>  
	        	    <th scope="col" align="centre">User Role</th>                         
	        	    <th scope="col" align="centre">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td>No User Found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
					<tr>
						<td>'.$row['stc_electronics_user_id'].'</td>
						<td>'.$row['stc_electronics_user_fullName'].'</td>
						<td>'.$row['stc_electronics_user_address'].'</td>
						<td>'.$row['stc_electronics_user_contact'].'</td>
						<td>'.$row['stc_electronics_user_email'].'</td>
						<td>'.$row['stc_electronics_user_contact'].'</td>
						<td>'.$row['stc_electronics_user_password'].'</td>
						<td>'.$row['stc_electronics_user_for'].'</td>
						<td>
							<a 
								id="'.$row['stc_electronics_user_id'].'"
								class="stc-user-mail-it"
								href="#" 
								style="font-size:20px; color:black;padding:3px;float:center;"
							><i class="fa fa-envelope-open"></i>
							</a>
							<a 
								id="'.$row['stc_electronics_user_id'].'"
								class="stc-user-role-it"
								href="#" 
								style="font-size:20px; color:black;padding:3px;float:center;"
							><i class="fa fa-key"></i>
							</a>
						</td>
					</tr>
				';	
			}	
			$result=mysqli_fetch_assoc($check_loki);
					
		}
		$odin.='
				</tbody>
	       	</table>
		';
		return $odin;
	}	

	// call user
	public function stc_search_euser_call(){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_electronics_user_id`,
				`stc_electronics_user_fullName`,
				`stc_electronics_user_address`,
				`stc_electronics_user_email`,
				`stc_electronics_user_contact`,
				`stc_electronics_user_password`,
				`stc_electronics_user_for`
			FROM
				`stc_electronics_user`
			ORDER BY `stc_electronics_user_fullName` ASC
		");
		$odin='
			<table class="table table-hover ">
	        	<thead>
	        	  <tr>
	        	    <th scope="col" align="centre">User Id</th>
	        	    <th scope="col" align="centre">User Name</th>
	        	    <th scope="col" align="centre" width="20%">User Address</th>
	        	    <th scope="col" align="centre">User Phone Number</th>
	        	    <th scope="col" align="centre">User Email</th>
	        	    <th scope="col" align="centre">User Uid</th>
	        	    <th scope="col" align="centre">User Password</th>  
	        	    <th scope="col" align="centre">User Role</th>                         
	        	    <th scope="col" align="centre">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td>No User Found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
					<tr>
						<td>'.$row['stc_electronics_user_id'].'</td>
						<td>'.$row['stc_electronics_user_fullName'].'</td>
						<td>'.$row['stc_electronics_user_address'].'</td>
						<td>'.$row['stc_electronics_user_contact'].'</td>
						<td>'.$row['stc_electronics_user_email'].'</td>
						<td>'.$row['stc_electronics_user_contact'].'</td>
						<td>'.$row['stc_electronics_user_password'].'</td>
						<td>'.$row['stc_electronics_user_for'].'</td>
						<td>
							<a 
								id="'.$row['stc_electronics_user_id'].'"
								class="stc-user-mail-it"
								href="#" 
								style="font-size:20px; color:black;padding:3px;float:center;"
							><i class="fa fa-envelope-open"></i>
							</a>
							<a 
								id="'.$row['stc_electronics_user_id'].'"
								class="stc-user-role-it"
								href="#" 
								style="font-size:20px; color:black;padding:3px;float:center;"
							><i class="fa fa-key"></i>
							</a>
						</td>
					</tr>
				';	
			}	
			$result=mysqli_fetch_assoc($check_loki);
					
		}
		$odin.='
				</tbody>
	       	</table>
		';
		return $odin;
	}

	// add user
	public function stc_euser_hit($user_name, $user_address, $user_phone, $user_email, $user_city, $user_state, $user_pincode, $user_about, $user_pass){
		$odin='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_electronics_user_id`
			FROM
			    `stc_electronics_user`
			WHERE
			    `stc_electronics_user_contact` = '".mysqli_real_escape_string($this->stc_dbs, $user_phone)."'
			OR 
				`stc_electronics_user_email` = '".mysqli_real_escape_string($this->stc_dbs, $user_email)."'
		");
		if(mysqli_num_rows($check_loki)==0){
			$set_loki=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_electronics_user`(
					`stc_electronics_user_fullName`,
					`stc_electronics_user_address`,
					`stc_electronics_user_email`,
					`stc_electronics_user_contact`,
					`stc_electronics_user_cityid`,
					`stc_electronics_user_stateid`,
					`stc_electronics_user_pincode`,
					`stc_electronics_user_aboutyou`,
					`stc_electronics_user_password`,
					`stc_electronics_user_for`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $user_name)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_address)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_email)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_phone)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_city)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_state)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_pincode)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_about)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_pass)."',
					'2'
				)
			");
			if($set_loki){
				$odin='User added succefully.';
			}else{
				$odin='Hmm!!! Something went wrong for adding a user.';
			}
		}else{
			$odin="This user is already exist.";
		}
		return $odin;
	}

	// role change
	public function stc_euser_role($uid, $urole){
		$odin='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_electronics_user_id`
			FROM
			    `stc_electronics_user`
			WHERE
			    `stc_electronics_user_id` = '".mysqli_real_escape_string($this->stc_dbs, $uid)."' 
			AND 
				`stc_electronics_user_for` = '".mysqli_real_escape_string($this->stc_dbs, $urole)."' 
		");

		if(mysqli_num_rows($check_loki)>0){
			$odin="User is already assigned for that role.";
		}else{
			$set_loki=mysqli_query($this->stc_dbs, "
				UPDATE
				    `stc_electronics_user`
				SET
				    `stc_electronics_user_for` = '".mysqli_real_escape_string($this->stc_dbs, $urole)."'
				WHERE
				    `stc_electronics_user_id` = '".mysqli_real_escape_string($this->stc_dbs, $uid)."'
			");
			if($set_loki){
				$odin="User assigned successfully.";
			}else{
				$odin="Hmm!!! Something went wrong on assigning the user.";
			}
		}
		return $odin;
	}
}
#<--------------------------------------------------------------------->
#<----------------------------Object Section--------------------------->
#<--------------------------------------------------------------------->
// view for edit purchase product hit
if(isset($_POST['stc_login_check'])){
	if(empty($_POST["stcusername"]) || empty($_POST["stcuserpassword"])){
	  	$message = "Both Fields are required";
	}else{
		$user=$_POST['stcusername'];
		$pass=$_POST['stcuserpassword'];
		$objlogin=new login_check();
		$opobjlogin=$objlogin->stc_login($user,$pass);
		echo $opobjlogin;
	}
}

// user load
if(isset($_POST['load_u'])){
	$objcalluser=new login_check();
	$opobobjcalluser=$objcalluser->stc_call_user();
	echo json_encode($opobobjcalluser);
}

// user update
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
		$objcalluser=new login_check();
		$opobobjcalluser=$objcalluser->stc_update_user( $username, $useraddress, $userphone, $userwhats, $useremail, $userid, $userpass1
		);
		
	}
	echo $opobobjcalluser;
}

// filters user
if(isset($_POST['search_user_name'])){
	$search = str_replace(",", "|", $_POST["search_user_name"]);
	$objloki=new user_call();
	$objlokiout=$objloki->stc_search_user($search);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// call user
if(isset($_POST['stc_call_users_on_load'])){
	$objloki=new user_call();
	$objlokiout=$objloki->stc_search_user_call();

	// echo json_encode($objlokiout);
	echo $objlokiout;
}

// user save
if(isset($_POST['stc_user_hit'])){
	$out='';
	$user_name=$_POST['user_name'];
	$user_address=$_POST['user_address'];
	$user_phone_number=$_POST['user_phone_number'];
	$user_whats_number=$_POST['user_whats_number'];
	$user_email=$_POST['user_email'];
	$user_uid='@'.str_replace(' ', '', $user_name);
	$user_pass='@'.str_replace(' ', '', $user_name);
	if(empty($user_name) || empty($user_address) || empty($user_phone_number) || empty($user_email)){
		$out="Please enter complete details.";
	}else{
		$thor=new user_call();
		$out=$thor->stc_user_hit($user_name, $user_address, $user_phone_number, $user_whats_number, $user_email, $user_uid, $user_pass);
	}
	echo $out;
}

// user role save
if(isset($_POST['stc_user_role_hit'])){
	$uid=$_POST['uid'];
	$urole=$_POST['role'];
	$thor=new user_call();
	$thor=$thor->stc_user_role($uid, $urole);
	echo $thor;
}

// user role save on table
if(isset($_POST['stc_roles_privilege'])){
	$user_id=$_POST['user_id'];
	$roles_val=$_POST['roles_val'];
	$thor=new user_call();
	$thorout=$thor->stc_user_privilege($user_id, $roles_val);
	echo $thorout;
}

// user role save on table
if(isset($_POST['stc_roles_privilege_get'])){
	$uid=$_POST['uid'];
	$thor=new user_call();
	$thorout=$thor->stc_user_privilege_get($uid);
	echo json_encode($thorout);
}

#<------------------Object electronics user Section-------------------->
// filters user
if(isset($_POST['search_euser_name'])){
	$search = str_replace(",", "|", $_POST["search_euser_name"]);
	$objloki=new electronics_user();
	$objlokiout=$objloki->stc_search_euser($search);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// call user
if(isset($_POST['stc_call_eusers_on_load'])){
	$objloki=new electronics_user();
	$objlokiout=$objloki->stc_search_euser_call();

	// echo json_encode($objlokiout);
	echo $objlokiout;
}

// user save
if(isset($_POST['stc_euser_hit'])){
	$out='';
	$user_name=$_POST['user_name'];
	$user_address=$_POST['user_address'];
	$user_phone=$_POST['user_phone'];
	$user_email=$_POST['user_email'];
	$user_city=$_POST['user_city'];
	$user_state=$_POST['user_state'];
	$user_pincode=$_POST['user_pincode'];
	$user_about=$_POST['user_about'];

	$user_pass=str_replace(' ', '', $user_name).'123';
	if(empty($user_name) || empty($user_address) || empty($user_phone) || empty($user_email)){
		$out="Please enter complete details.";
	}else{
		$thor=new electronics_user();
		$out=$thor->stc_euser_hit($user_name, $user_address, $user_phone, $user_email, $user_city, $user_state, $user_pincode, $user_about, $user_pass);
	}
	echo $out;
}

// user role save
if(isset($_POST['stc_euser_role_hit'])){
	$uid=$_POST['uid'];
	$urole=$_POST['role'];
	$thor=new electronics_user();
	$thor=$thor->stc_euser_role($uid, $urole);
	echo $thor;
}
?>