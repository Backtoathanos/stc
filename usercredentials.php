<?php 
    session_start();
    include_once "MCU/db.php";
    $user_type=$_GET['user_type'];
    $user_id=$_GET['user_id'];
    
    $options=0;
    $ciphering = "AES-128-CTR";
    $decryption_iv = '1234567891011121';
    $decryption_key = "stc_associate_go";
    $decryption=openssl_decrypt ($user_id, $ciphering, $decryption_key, $options, $decryption_iv);
    if($user_type=="siteuser"){
        $checkbumblebee=mysqli_query($con, "
			SELECT `stc_cust_pro_supervisor_id`, `stc_cust_pro_supervisor_fullname`, `stc_cust_pro_supervisor_category` 
			FROM `stc_cust_pro_supervisor` 
			WHERE `stc_cust_pro_supervisor_id`='".$decryption."'
		");
        if(mysqli_num_rows($checkbumblebee)>0){
            $user_details=mysqli_fetch_assoc($checkbumblebee);
            $user_name=$user_details['stc_cust_pro_supervisor_fullname'];
            $user_id=$user_details['stc_cust_pro_supervisor_id'];
            $category=$user_details['stc_cust_pro_supervisor_category'];
            $_SESSION['stc_agent_sub_id']=$user_id;
            $_SESSION['stc_agent_sub_name']=$user_name;
            $_SESSION['stc_agent_sub_category']=$category;
            header('Location:stc_sub_agent47/dashboard.php');
        }else{
            header('Location:404.shtml');
        }
    }elseif($user_type=="manager"){
        $checkbumblebee=mysqli_query($con, "
            SELECT * FROM `stc_agents` 
            WHERE `stc_agents_id`='".$decryption."'
		");
        if(mysqli_num_rows($checkbumblebee)>0){
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$user_name=$user_details['stc_agents_name'];
			$user_id=$user_details['stc_agents_id'];
			$_SESSION['stc_agent_id']=$user_id;
			$_SESSION['stc_agent_name']=$user_name;
			$_SESSION['stc_agent_role']=$user_details['stc_agents_role'];
            header('Location:stc_agent47/dashboard.php');
        }else{
            header('Location:404.shtml');
        }
    }elseif($user_type=="uadmin"){
        $checkbumblebee=mysqli_query($con, "
            SELECT 
                `stc_user_id`,
                `stc_user_name`,
                `stc_user_role`
            FROM `stc_user` 
            WHERE `stc_user_id`='".$decryption."'
		");
        if(mysqli_num_rows($checkbumblebee)>0){
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$user_name=$user_details['stc_user_name'];
			$user_id=$user_details['stc_user_id'];
			$_SESSION['stc_empl_id']=$user_id;
			$_SESSION['stc_empl_name']=$user_name;
			$_SESSION['stc_empl_role']=$user_details['stc_user_role'];
            header('Location:stc_vikings/dashboard.php');
        }else{
            echo $decryption;
            // header('Location:404.shtml');
        }
    }elseif($user_type=="school"){
        $checkbumblebee=mysqli_query($con, "
            SELECT * FROM `stc_school` 
            WHERE `stc_school_user_id`='".$decryption."'
        ");
        if(mysqli_num_rows($checkbumblebee)>0){
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$user_name=$user_details['stc_school_user_fullName'];
			$user_id=$user_details['stc_school_user_id'];
			$user_for=$user_details['stc_school_user_for'];
			// setcookie("agentsperforme", $user_id, time() +3600000);
			// setcookie("agentsperformename", $user_name, time() + 3600000);
			$checkbumblebeeagain=mysqli_query($con, "
				SELECT `stc_school_teacher_id` FROM `stc_school`
				LEFT JOIN `stc_school_teacher`
				ON `stc_school_teacher_userid`=`stc_school_user_id`
				WHERE `stc_school_user_id`='".mysqli_real_escape_string($con, $user_id)."'
			");
			$teacher_id='';
			foreach($checkbumblebeeagain as $checkbumblebeeagainrow){
				$teacher_id=$checkbumblebeeagainrow['stc_school_teacher_id'];
			}
			$_SESSION['stc_school_user_id']=$user_id;
			$_SESSION['stc_school_user_name']=$user_name;
			$_SESSION['stc_school_user_for']=$user_for;
			$_SESSION['stc_school_teacher_id']=$teacher_id;
            header('Location:stc_umbrella/stc_mazeRunner/dashboard.php');
        }else{
            header('Location:404.shtml');
        }
    }elseif($user_type=="groceries"){
        $checkbumblebee=mysqli_query($con, "
            SELECT * FROM `stc_groceries_user` 
            WHERE `stc_groceries_user_id`='".$decryption."'
        ");
        if(mysqli_num_rows($checkbumblebee)>0){
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$user_name=$user_details['stc_groceries_user_name'];
			$user_id=$user_details['stc_groceries_user_id'];
			// setcookie("agentsperforme", $user_id, time() +3600000);
			// setcookie("agentsperformename", $user_name, time() + 3600000);
			$_SESSION['stc_groceries_user_id']=$user_id;
			$_SESSION['stc_groceries_user_name']=$user_name;
            header('Location:stc_groceries/stc_mazeRunner/dashboard.php');
        }else{
            header('Location:404.shtml');
        }
    }elseif($user_type=="electronics"){
        $checkbumblebee=mysqli_query($con, "
            SELECT * FROM `stc_electronics_user` 
            WHERE `stc_electronics_user_id`='".$decryption."'
        ");
        if(mysqli_num_rows($checkbumblebee)>0){
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$user_name=$user_details['stc_electronics_user_fullName'];
			$user_id=$user_details['stc_electronics_user_id'];
			$user_for=$user_details['stc_electronics_user_for'];
			// setcookie("agentsperforme", $user_id, time() +3600000);
			// setcookie("agentsperformename", $user_name, time() + 3600000);
			$_SESSION['stc_electro_user_id']=$user_id;
			$_SESSION['stc_electro_user_name']=$user_name;
			$_SESSION['stc_electro_user_for']=$user_for;
            header('Location:stc_maze/stc_mazeRunner/dashboard.php');
        }else{
            header('Location:404.shtml');
        }
    }elseif($user_type=="trading"){
        $checkbumblebee=mysqli_query($con, "
            SELECT * FROM `stc_trading_user` 
            WHERE `stc_trading_user_id`='".$decryption."'
        ");
        if(mysqli_num_rows($checkbumblebee)>0){
			$user_details=mysqli_fetch_assoc($checkbumblebee);
			$user_name=$user_details['stc_trading_user_name'];
			$user_id=$user_details['stc_trading_user_id'];
			// setcookie("agentsperforme", $user_id, time() +3600000);
			// setcookie("agentsperformename", $user_name, time() + 3600000);
			$_SESSION['stc_trading_user_id']=$user_id;
			$_SESSION['stc_trading_user_name']=$user_name;
            header('Location:stc_trading/stc_mazeRunner/dashboard.php');
        }else{
            header('Location:404.shtml');
        }
    }

?>