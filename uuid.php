<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
require_once '../../includes/functions.php';

        $data = "";
        $data .= '{';
        $data .= '"inbounds": [';
        $data .= '{';
        $data .= '"port": 10000,';
        $data .= '"listen":"127.0.0.1",';
        $data .= '"protocol": "vmess",';
        $data .= '"settings": {';
        $data .= '"clients": [';
                
            $query = $db->sql_query("SELECT uuid FROM users WHERE duration > 0 AND is_freeze = 0 AND uuid != '' AND user_id > 2 ORDER by user_id DESC");
            $query2 = $db->sql_query("SELECT path FROM site_options"); 
            $row2 = $db->sql_fetchrow($query2);
            $path = $row2['path'];
                
                while( $row = $db->sql_fetchrow($query) )
                {
                    $data .= "{";
                	$uuid = $row['uuid'];				
                	$data .= '"id": "'.$uuid.'",';
                	$data .= '"alterId": 0';
                	$data .= "},";
                }
                
        $data .= "{";
        $data .= '"id": "8a3117db-6f73-480a-9b3e-fdaa12a020eb",';
        $data .= '"alterId": 0';
        $data .= "}";
        $data .= ']';
        $data .= '},';
        $data .= '"streamSettings": {';
        $data .= '"network": "ws",';
        $data .= '"wsSettings": {';
        $data .= '"path": "/'.$path.'"';
        $data .= '}';
        $data .= '}';
        $data .= '}';
        $data .= '],';
                
        $data .= '"outbounds": [';
        $data .= '{';
        $data .= '"protocol": "freedom",';
        $data .= '"settings": {}';
        $data .= '}';
        $data .= ']';
        $data .= '}';
        
    echo $data;

?>
