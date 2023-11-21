<?php
session_start();
	$sesionname=@$_SESSION['stc_admin_info_id'];
	$logout=session_destroy();
	if($logout){
		header('location:../../index.html');
	}else{
		echo "No logout";
	}
?>