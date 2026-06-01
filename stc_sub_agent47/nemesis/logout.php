<?php
session_start();
require_once __DIR__ . '/../includes/agent_session_defaults.php';
	$sesionname=@$_SESSION['stc_agent_sub_id'];
	$logout=session_destroy();
	if($logout){
		header('location:../../index.html');
	}else{
		echo "No logout";
	}
?>