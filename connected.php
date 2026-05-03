<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
require_once '../includes/functions.php';

if($_GET['total'] == '0'){
    $total = '0';
}else{
    $total = strip_tags(trim($_GET['total']));
}
 	$ip = strip_tags(trim($_GET['ip']));
 

 		$sql = "UPDATE server_list SET online='$total' WHERE server_ip='$ip'";
 		$qry = $db->sql_query($sql) or die();
 		
 	    if($qry){
 	        echo 'success';
 	    }else{
 	        echo 'failed';
    }
?>