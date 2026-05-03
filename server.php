<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
require_once '../includes/functions.php';

if($_GET['ip']){
    
    $ip = $_GET['ip'];
    $sql = "SELECT server_name FROM server_list WHERE server_ip='$ip'";
 	$result = $db->sql_query($sql);
 	
 	if ($db->sql_fetchrow($result) > 0){
 	    $sql2 = $db->sql_query("SELECT * FROM server_list WHERE server_ip='$ip'");
 		$row = $db->sql_fetchrow($sql2);
 		
 		$json_data = array(
            "success" => true,
            "message" => 'Success',
            "data" => array(
                "server_ip" => $row['server_ip'],
                "server_name" => $row['server_name'],
                "server_status" => $row['status']));
        echo json_encode($json_data);
        
 	}else{
        $json_data = array(
     	    "success" => false,
            "message" => 'Invalid Server Address');
        echo json_encode($json_data);
 	}
}else{
    $json_data = array(
 	    "success" => false,
        "message" => 'Invalid Request');
    echo json_encode($json_data);
}
?>