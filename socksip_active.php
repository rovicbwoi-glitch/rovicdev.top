<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
require_once '../../includes/functions.php';

if(isset($_GET['key']) && !empty($_GET['key'])){
    
    $key = $_GET['key'];
    
    if($key == '@@@PANEL@@@') {
        $data = '';
        $query = $db->sql_query("SELECT user_name, user_pass, duration FROM users WHERE duration > 0 AND is_freeze = 0 AND is_socksip = 1 AND user_level != 'superadmin' AND user_level != 'developer' AND user_level != 'reseller' ORDER by user_id DESC");
        
        while( $row = $db->sql_fetchrow($query) )
        {
        	$data .= '';
        	$username = $row['user_name'];
        	$user_pass = $db->decrypt_key($row['user_pass']);
        	$user_pass = $db->encryptor('decrypt',$user_pass);
        	$date = date("Y-m-d", strtotime("+ 30 days"));
        	
        	$userduration = $row['duration'];
        	$dur = $db->calc_time($userduration);	
        	$pdays = $dur['days'] . " days";
        	$phours = $dur['hours'] . " hours";
        	$pminutes = $dur['minutes'] . " minutes";
        	$pseconds = $dur['seconds'] . " seconds";
        	
        	$duration = strtotime($pdays . $phours . $pminutes . $pseconds);
		    $date = date('Y-m-d', $duration);
        	
        	//$data .= '/usr/sbin/useradd -p $(openssl passwd -1 '.$user_pass.') -s /bin/false -M '.$username.' &> /dev/null;'.PHP_EOL;
        	$data .= 'useradd -M -s /bin/false -e '.$date.' -p $(openssl passwd -1 '.$user_pass.') -c 1,'.$username.' '.$username.''.PHP_EOL;
        }
        
        echo $data;
    }else{
        echo 'Invalid Key!';
    }
}else{
    echo 'Invalid Key!';
}
?>