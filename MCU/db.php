<?php
date_default_timezone_set('Asia/Kolkata');
$server="localhost";
$user="nausher";
$password="ZX5#*,PPq$5;";
$db="stc_associate_go";
// $db="dev_stc_associate_go";
$con=mysqli_connect($server,$user,$password,$db) or die("could not connect");
mysqli_set_charset($con,'utf8');

?>
