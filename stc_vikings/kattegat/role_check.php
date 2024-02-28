<?php
include "../MCU/db.php";
	$role_check_qry=mysqli_query($con, "
		SELECT
			`stc_user_role_uid`,
			`stc_user_role_privilege_id`
		FROM
			`stc_user_role`
		WHERE
			`stc_user_role_uid`='".$_SESSION['stc_empl_id']."'
		AND 
			`stc_user_role_privilege_id`='".$page_code."'
    ");

    if(mysqli_num_rows($role_check_qry)==0){
    	header("Location:forbidden.html");
    }

	$role_get_qry=mysqli_query($con, "
		SELECT
			`stc_user_role_uid`,
			`stc_user_role_privilege_id`
		FROM `stc_user_role`
		WHERE `stc_user_role_uid`='".$_SESSION['stc_empl_id']."'
    ");
	$checkrole_array = array();

	foreach ($role_get_qry as $role_get_row) {
		$checkrole_array[] = $role_get_row['stc_user_role_privilege_id'];
	}
?>