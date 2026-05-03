<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
require_once '../includes/functions.php';
 
 if(isset($_GET['username'],$_GET['password'],$_GET['device_id'],$_GET['device_model'])){
 	$username = strip_tags(trim($_GET['username']));
 	$password = strip_tags(trim($_GET['password']));
 	$device_id = strip_tags(trim($_GET['device_id']));
 	$device_model = strip_tags(trim($_GET['device_model']));
 
 	if(empty($_GET['username'])){
 		$LenzData[value] = '';
        $LenzData[msg] = 'Error occured.';
        $LenzData[param] = 'username';
        $LenzData[location] = 'query';
        $data[] = $LenzData;
        $json_data = array(
    			        "status" => 'invalid',
    			        "errors" => ( $data ));
        echo json_encode($json_data);
 	}elseif(empty($_GET['password'])){
 		$LenzData[value] = '';
        $LenzData[msg] = 'Error occured.';
        $LenzData[param] = 'password';
        $LenzData[location] = 'query';
        $data[] = $LenzData;
        $json_data = array(
    			        "status" => 'invalid',
    			        "errors" => ( $data ));
        echo json_encode($json_data);
 	}elseif(empty($_GET['device_id'])){
 		$LenzData[value] = '';
        $LenzData[msg] = 'Error occured.';
        $LenzData[param] = 'device_id';
        $LenzData[location] = 'query';
        $data[] = $LenzData;
        $json_data = array(
    			        "status" => 'invalid',
    			        "errors" => ( $data ));
        echo json_encode($json_data);
 	}elseif(empty($_GET['device_model'])){
 		$LenzData[value] = '';
        $LenzData[msg] = 'Error occured.';
        $LenzData[param] = 'device_model';
        $LenzData[location] = 'query';
        $data[] = $LenzData;
        $json_data = array(
    			        "status" => 'invalid',
    			        "errors" => ( $data ));
        echo json_encode($json_data);
 	}elseif(!empty($_GET['username']) && !empty($_GET['password']) && !empty($_GET['device_id']) && !empty($_GET['device_model'])){
 		
 		$qry = $db->sql_query("SELECT duration FROM users WHERE user_name='".$username."' LIMIT 1");
        $row = $db->sql_fetchrow($qry);
        
        $dur = $db->calc_time($row['duration']);
        $pdays = $dur['days'] . " days";
		$phours = $dur['hours'] . " hours";
		$pminutes = $dur['minutes'] . " minutes";
		$pseconds = $dur['seconds'] . " seconds";
		
		$premuim_duration = strtotime($pdays . $phours . $pminutes . $pseconds);
        $premuim_duration = date('Y-m-d h:i:s', $premuim_duration);
 		
 		$sql = "SELECT user_name  FROM users WHERE user_name='$username' AND auth_vpn=md5('$password') AND is_freeze=0 AND duration > 0";
 		$result = $db->sql_query($sql);
 		
 		if ($db->sql_fetchrow($result) > 0){
 		    
 		        $sql2 = $db->sql_query("SELECT device_id FROM users WHERE user_name='$username' AND auth_vpn=md5('$password')");
 		        $result2 = $db->sql_fetchrow($sql2);
 		        
 		        $dev_id = $result2['device_id'];
 		        $dev_model = $result2['device_model'];
 		        
         		if($dev_id == ''.$device_id.'' && $dev_id != '') {
         			$json_data = array(
                                    "auth" => true,
                                    "expiry" => $premuim_duration,
                                    "device_match" => true);
                    echo json_encode($json_data);
                    
                    $db->sql_query("UPDATE users SET device_id = '$device_id', device_model = '$device_model', device_connected=1 WHERE user_name='$username'");
         		}else
         		if($dev_id != $device_id && $dev_id != ''){
         		    $json_data = array(
                                    "auth" => true,
                                    "expiry" => $premuim_duration,
                                    "device_match" => false);
                    echo json_encode($json_data);
         	    }else
         		if($dev_id == ''){
         		    $json_data = array(
                                    "auth" => true,
                                    "expiry" => $premuim_duration,
                                    "device_match" => true);
                    echo json_encode($json_data);
                    
                    $db->sql_query("UPDATE users SET device_id = '$device_id', device_model = '$device_model', device_connected=1 WHERE user_name='$username'");
         	    }else{
         			$json_data = array(
                                    "auth" => false,
                                    "expiry" => none,
                                    "device_match" => none);
                    echo json_encode($json_data);
         		}
 		}else{
         		    
         			$json_data = array(
                                    "auth" => false,
                                    "expiry" => none,
                                    "device_match" => none);
                    echo json_encode($json_data);
         		}
 	}else{
 	    
 		$json_data = array(
                        "auth" => false,
                        "expiry" => none,
                        "device_match" => none);
        echo json_encode($json_data);
 	}
 }else{
     
 	$json_data = array(
                    "auth" => false,
                    "expiry" => none,
                    "device_match" => none);
    echo json_encode($json_data);
 }
 
 ?>