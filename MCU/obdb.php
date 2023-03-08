<?php

// databaseconnectiviy
define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'');
define('DB_NAME', 'stc_associate_go');
class tesseract {
	function __construct(){
	$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
	mysqli_set_charset($con,'utf8');
	$this->stc_dbs=$con;
	// Check connection
		if (mysqli_connect_errno()){
			echo "Failed to connect to Database: " . mysqli_connect_error();
		}
	}
}

?>


