<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
require_once '../includes/functions.php';
	
if(isset($_GET['json'])){
 	$json = strip_tags(trim($_GET['json']));
 
 	if(empty($json)){
        echo 'Request was denied.';
 	}else{
 		$sqll = "SELECT * FROM json_update WHERE encryption='$json'";
 		$qryl = $db->sql_query($sqll) or die();
 		$total = $db->sql_numrows($qryl);
 	    
 	    if($total > 0){
 	        $file = "../uploads/json/$json.json";
 	        $file_ = file_get_contents($file);
 	        echo $file_;
 	    }else{
 	        echo 'Request was denied.';
 	    }
 	}
}else{
 	echo 'Request was denied.';
}
?>