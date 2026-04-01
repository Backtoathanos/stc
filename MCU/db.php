<?php
date_default_timezone_set('Asia/Kolkata');
// Docker / EC2: set DB_HOST=mysql, DB_USER, DB_PASSWORD, DB_NAME via environment (see docker-compose.yml)
$server = getenv('DB_HOST') ?: 'mysql-service';
$user = getenv('DB_USER') ?: 'nausher';
$password = getenv('DB_PASSWORD') ?: 'ZX5#*,PPq$5;';
$db = getenv('DB_NAME') ?: 'stc_associate_go';
$con=mysqli_connect($server,$user,$password,$db) or die("could not connect");
mysqli_set_charset($con,'utf8');

?>
