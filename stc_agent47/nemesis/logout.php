<?php
session_start();
    $_SESSION['stc_agent_id']='';
	if(empty($_SESSION['stc_agent_id'])){
	    session_destroy();
		header('location:../index.html');
	}else{
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
?>