<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
require_once '../includes/functions.php';
	
	
 if(isset($_GET['ip']) && isset($_GET['service'])){
 	$port_tcp = strip_tags(trim($_GET['tcp']));
 	$port_udp = strip_tags(trim($_GET['udp']));
 	$port_ssl = strip_tags(trim($_GET['ssl']));
 	$port_vless = strip_tags(trim($_GET['vless']));
 	$obfs = strip_tags(trim($_GET['obfs']));
 	$authstr = strip_tags(trim($_GET['authstr']));
 	$hysteria = strip_tags(trim($_GET['hysteria']));
 	$service = strip_tags(trim($_GET['service']));
 	$serverip = strip_tags(trim($_GET['ip']));
    
    $SUB_DOMAIN = strip_tags(trim($_GET['subdomain']));
    $NS_DOMAIN = strip_tags(trim($_GET['nsdomain']));
    
 	if(empty($serverip) || empty($service)){
        echo 'Request was denied.';
 	}else{
 	    
 	    // Define all supported services
        $services = [
            'debian_aio', 'ubuntu_aio',
            'debian_ovpn', 'debian_ovpnws',
            'debian_openconnect', 'debian_openconnectws',
            'ubuntu_ovpn', 'ubuntu_ovpnws',
            'ubuntu_openconnect', 'ubuntu_openconnectws',
            'centos_ovpn', 'centos_ovpnws'
        ];
        
        if (in_array($service, $services)) {
            $scriptContent  = "#!/bin/bash" . PHP_EOL;
            $scriptContent .= "# Script Variables" . PHP_EOL;
            $scriptContent .= "HOST='$DB_ip';" . PHP_EOL;
            $scriptContent .= "USER='$DB_user';" . PHP_EOL;
            $scriptContent .= "PASS='$DB_pass';" . PHP_EOL;
            $scriptContent .= "DBNAME='$DB_name';" . PHP_EOL;
            $scriptContent .= "PORT_TCP='$port_tcp';" . PHP_EOL;
            $scriptContent .= "PORT_UDP='$port_udp';" . PHP_EOL;
            $scriptContent .= "PORT_SSL='$port_ssl';" . PHP_EOL;
            $scriptContent .= "OBFS='$obfs';" . PHP_EOL;
            $scriptContent .= "HYSTERIA_TYPE='$hysteria';" . PHP_EOL;
        
            // Extra configs for aio services
            if (in_array($service, ['debian_aio', 'ubuntu_aio'])) {
                $scriptContent .= "API_LINK='https://$site_url/api/authentication';" . PHP_EOL;
                $scriptContent .= "API_KEY='@@@DEX@@@';" . PHP_EOL;
                $scriptContent .= "DOMAIN=$SUB_DOMAIN" . PHP_EOL;
                $scriptContent .= "NS=$NS_DOMAIN" . PHP_EOL;
            }
        

            
 	    }elseif($service == 'debian_xray'){
 	        $vmesslink = ran_code();
            $vlesslink = ran_code();
            $trojanlink = ran_code();
            $sslink = ran_code();
 	        
 	        $scriptContent = "#!/bin/bash".PHP_EOL;
            $scriptContent .= "#Script Variables".PHP_EOL;
            $scriptContent .= "HOST='$DB_ip';".PHP_EOL;
            $scriptContent .= "USER='$DB_user';".PHP_EOL;
            $scriptContent .= "PASS='$DB_pass';".PHP_EOL;
            $scriptContent .= "DBNAME='$DB_name';".PHP_EOL;
            $scriptContent .= "VMESS_LINK='$vmesslink';".PHP_EOL;
            $scriptContent .= "VLESS_LINK='$vlesslink';".PHP_EOL;
            $scriptContent .= "TROJAN_LINK='$trojanlink';".PHP_EOL;
            $scriptContent .= "SS_LINK='$sslink';".PHP_EOL;
            $scriptContent .= "DOMAIN=$SUB_DOMAIN".PHP_EOL;
            $scriptContent .= "API_LINK='https://$site_url/api/authentication';".PHP_EOL;
            $scriptContent .= "API_KEY='@@@DEX@@@';".PHP_EOL;
            
 	    }elseif (in_array($service, ['debian_ssh' , 'ubuntu_ssh'])) {
            $scriptContent  = "#!/bin/bash" . PHP_EOL;
            $scriptContent .= "# Script Variables" . PHP_EOL;
            $scriptContent .= "HOST='$DB_ip';" . PHP_EOL;
            $scriptContent .= "USER='$DB_user';" . PHP_EOL;
            $scriptContent .= "PASS='$DB_pass';" . PHP_EOL;
            $scriptContent .= "DBNAME='$DB_name';" . PHP_EOL;
            $scriptContent .= "API_LINK='https://$site_url/api/authentication';" . PHP_EOL;
            $scriptContent .= "API_KEY='@@@DEX@@@';" . PHP_EOL;
            
 	    }elseif($service == 'debian_psiphon'){
 	        
 	        $secrt = ran_code();
 	        $scriptContent = "#!/bin/bash".PHP_EOL;
            $scriptContent .= "#Script Variables".PHP_EOL;
            $scriptContent .= "HOST='$DB_ip';".PHP_EOL;
            $scriptContent .= "USER='$DB_user';".PHP_EOL;
            $scriptContent .= "PASS='$DB_pass';".PHP_EOL;
            $scriptContent .= "DBNAME='$DB_name';".PHP_EOL;
            $scriptContent .= "SECRET='$secrt';".PHP_EOL;
            
 	    }elseif($service == 'debian_hysteria' || $service == 'ubuntu_hysteria' || $service == 'centos_hysteria'){
 	        
 	        $scriptContent = "#!/bin/bash".PHP_EOL;
            $scriptContent .= "#Script Variables".PHP_EOL;
            $scriptContent .= "HOST='$DB_ip';".PHP_EOL;
            $scriptContent .= "USER='$DB_user';".PHP_EOL;
            $scriptContent .= "PASS='$DB_pass';".PHP_EOL;
            $scriptContent .= "DBNAME='$DB_name';".PHP_EOL; 
            $scriptContent .= "OBFS='$obfs';".PHP_EOL;
            $scriptContent .= "HYSTERIA_TYPE='$hysteria';".PHP_EOL;
            
 	    }elseif($service == 'debian_hysteria_free' || $service == 'ubuntu_hysteria_free' || $service == 'centos_hysteria_free'){
 	        
 	        $scriptContent = "#!/bin/bash".PHP_EOL;
            $scriptContent .= "#Script Variables".PHP_EOL;
            $scriptContent .= "HOST='$DB_ip';".PHP_EOL;
            $scriptContent .= "USER='$DB_user';".PHP_EOL;
            $scriptContent .= "PASS='$DB_pass';".PHP_EOL;
            $scriptContent .= "DBNAME='$DB_name';".PHP_EOL;
            $scriptContent .= "OBFS='$obfs';".PHP_EOL;
            $scriptContent .= "AUTHSTR='$authstr';".PHP_EOL;
            $scriptContent .= "HYSTERIA_TYPE='$hysteria';".PHP_EOL;
            
 	    }elseif($service == 'ubuntu_socksip' || $service == 'debian_socksip'){
 	        
 	        $scriptContent = "#!/bin/bash".PHP_EOL;
            $scriptContent .= "#Script Variables".PHP_EOL;
            $scriptContent .= "HOST='$DB_ip';".PHP_EOL;
            $scriptContent .= "USER='$DB_user';".PHP_EOL;
            $scriptContent .= "PASS='$DB_pass';".PHP_EOL;
            $scriptContent .= "DBNAME='$DB_name';".PHP_EOL;
            $scriptContent .= "API_LINK='https://$site_url/api/authentication';".PHP_EOL;
            $scriptContent .= "API_KEY='@@@DEX@@@';".PHP_EOL;
 	    }
 	    
 	    $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://raw.githubusercontent.com/$git_username/old_panel/main/$service");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        
        
        $headers = array();
        $headers[] = "Authorization: token $git_token";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        
        $contentScript = base64_encode($scriptContent.$data);
            
     	pushFile("$git_username","$git_token","$git_username/$site_url","server_script","$service","$contentScript");
 	    
 	}
 }else{
 	echo 'Request was denied.';
}
?>