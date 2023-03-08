<?php
session_start();
	$sesionname=@$_SESSION['stc_school_user_id'];
	$logout=session_destroy();
	if($logout){		
		header('location:../index.html');
	}else{
		echo "No logout";
	}
?>