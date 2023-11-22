<?php

$server="localhost";
$user="root";
$password="";
$db="stc_associate_go";
$con=mysqli_connect($server,$user,$password,$db) or die("could not connect");
mysqli_set_charset($con,'utf8');

?>
