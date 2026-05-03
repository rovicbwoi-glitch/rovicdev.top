<?php
date_default_timezone_set('Asia/Riyadh');
$DB_ip = "66.45.238.235";
$DB_host = "localhost";
$DB_user = "dxpluson_rovicdev";
$DB_pass = "dxpluson_rovicdev"; 
$DB_name = "dxpluson_rovicdev";

$mysqli = new MySQLi($DB_host,$DB_user,$DB_pass,$DB_name);
if($mysqli->connect_error){
   die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
?>