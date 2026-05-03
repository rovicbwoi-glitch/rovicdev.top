<?php

chkSession();
if($user_id_2 == 1 || $user_level_2 == 'superadmin'){
	
}else{
	header("Location: /dashboard");	
}

$requestData= $_REQUEST;
if(empty($requestData)){
	$db->RedirectToURL($db->base_url());
	exit;	
}

$columns = array( 
    0	=> 'server_id',
	1	=> 'server_name',
	2	=> 'server_ip',
	3	=> null
);

$sql = "SELECT * FROM server_list";
$query = $db->sql_query($sql) or die();
$totalData = $db->sql_numrows($query);
$totalFiltered = $totalData;

$sql = "SELECT * FROM server_list WHERE 1=1";

if( !empty($requestData['search']['value']) ) { 
	$sql.=" AND ( server_id LIKE '%".$requestData['search']['value']."%' "; 
	$sql.=" OR server_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR server_ip LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = $db->sql_query($sql) or die();
$totalFiltered = $db->sql_numrows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']." ";

$query = $db->sql_query($sql) or die();

$data = array();
while( $row = $db->sql_fetchrow($query) ) {
	$nestedData=array();
	$server_id = $row['server_id'];
	$server_name = $row['server_name'];
	$server_ip = $row['server_ip'];
	$server_flag = $row['flag'];
	$protocol = $row['protocol'];
	$status = $row['status'];
	
	$online = $row['online'];
	$online2 = $row['hysteria_online'];
	$online3 = $row['ssh_online'];
	$total_online = $online + $online2 + $online3;
	
	if($status == 1){
	    $stat = 'Active';
	    $server_icon = 'checked';
	    $disab = '';
	    $servstat = 'online';
	}elseif($status == 0){
	    $stat = 'Inactive';
	    $server_icon = 'cancel';
	    $disab = 'disabled';
	    $servstat = 'busy';
	}elseif($status == 98){
	    $stat = 'Restarting';
	    $server_icon = 'synchronize';
	    $disab = 'disabled';
	    $servstat = 'offline';
	}elseif($status == 99){
	    $stat = 'Installing';
	    $server_icon = 'tools';
	    $disab = 'disabled';
	    $servstat = 'away';
	}else{
	    $stat = 'Inactive';
	    $server_icon = 'cancel';
	    $disab = 'disabled';
	    $servstat = 'busy';
	}
	
	if($protocol == 1){
	    $service = 'Openvpn (http)';
	    $os_icon = 'ubuntu_icon';
	}elseif($protocol == 2){
	    $service = 'Openconnect (http)';
	    $os_icon = 'ubuntu_icon';
	}elseif($protocol == 4){
	    $service = 'Openvpn (ws)';
	    $os_icon = 'ubuntu_icon';
	}elseif($protocol == 5){
	    $service = 'Openconnect (ws)';
	    $os_icon = 'ubuntu_icon';
	}elseif($protocol == 8){
	    $service = 'Openvpn (http)';
	    $os_icon = 'debian_icon';
	}elseif($protocol == 9){
	    $service = 'Openconnect (http)';
	    $os_icon = 'debian_icon';
	}elseif($protocol == 11){
	    $service = 'Openvpn (ws)';
	    $os_icon = 'debian_icon';
	}elseif($protocol == 81){
	    $service = 'Debian Ssh (ws)';
	    $os_icon = 'debian_icon';
	}elseif($protocol == 12){
	    $service = 'Openconnect (ws)';
	    $os_icon = 'debian_icon';
	}elseif($protocol == 13){
	    $service = 'openvpn aio(ws)';
	    $os_icon = 'debian_icon';
	}elseif($protocol == 15){
	    $service = 'Openvpn (http)';
	    $os_icon = 'centos_icon';
	}elseif($protocol == 18){
	    $service = 'Openvpn (ws)';
	    $os_icon = 'centos_icon';
	}elseif($protocol == 31){
	    $service = 'Xray';
	    $os_icon = 'debian_icon';
	}elseif($protocol == 41){
	    $service = 'Hysteria (udp)';
	    $os_icon = 'ubuntu_icon';
	}elseif($protocol == 42){
	    $service = 'Hysteria (udp)';
	    $os_icon = 'debian_icon';
	}elseif($protocol == 43){
	    $service = 'Hysteria (udp)';
	    $os_icon = 'centos_icon';
	}elseif($protocol == 51){
	    $service = 'Socksip (udp)';
	    $os_icon = 'ubuntu_icon';
	}elseif($protocol == 62){
	    $service = 'Hysteria (Free)';
	    $os_icon = 'debian_icon';
	}else{
	    $service = 'undefined';
	    $os_icon = 'cancel';
	}
    
    $nestedData[] = '<div class="custom-checkbox custom-control">
                    <input type="checkbox" class="custom-control-input checkbox-child" value="'.$server_ip.'" id="select-this-'.$db->encryptor('encrypt',$server_id).'" data-chk="chk[]" name="chk[]" data-selectid="'.$db->encryptor('encrypt',$server_id).'">
                    <label for="select-this-'.$db->encryptor('encrypt',$server_id).'" class="custom-control-label">&nbsp;</label>
                    </div>';
    
    
	$nestedData[] = '<figure class="avatar avatar-sm"><img src="/dist/img/world-globe.png" alt="servericon"><i class="avatar-presence '.$servstat.'"></i></figure> <strong>'.strtoupper($server_name).'</strong>';
	$nestedData[] = '<figure class="avatar avatar-sm"><img src="/dist/img/'.$os_icon.'.png" alt="osicon"></figure> <strong>'.strtoupper($service).'</strong>';
	$nestedData[] = '<figure class="avatar avatar-sm"><img src="/dist/img/'.$server_icon.'.png" alt="onlineicon"></figure> <strong>'.strtoupper($stat).' - <i class="fas fa-users"></i> '.$total_online.'</strong>';
	$nestedData[] = '<figure class="avatar avatar-sm"><img src="/dist/img/flags/'.strtolower($server_flag).'.svg" alt="flagicon"></figure> <strong>'.$server_ip.'</strong>';
	$nestedData[] = '<div class="btn-group btn-group-md" role="group">
                    <button type="button" class="btn btn-outline-primary btn-server-stats" data-ip="'.$server_ip.'" data-name="'.$server_name.'" data-type="'.strtoupper($service).'" '.$disab.'><i class="fas fa-info-circle"></i></button>
                    <button type="button" class="btn btn-outline-primary btn-server-restart " id="restart-btn-'.$db->encryptor('encrypt',$server_id).'" data-type="'.strtoupper($service).'" data-ip="'.$server_ip.'" data-name="'.$server_name.'" '.$disab.'><i class="fas fa-retweet"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-server-delete" id="delete-btn-'.$db->encryptor('encrypt',$server_id).'" data-type="'.strtoupper($service).'" data-ip="'.$server_ip.'" data-name="'.$server_name.'"><i class="fas fa-trash-alt"></i></button>
                    </div>';

	$data[] = $nestedData;	
}

$json_data = array(
			"draw"            => intval( $requestData['draw'] )? intval( $_REQUEST['draw'] ) : 0,
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => ($data )
			);

echo json_encode($json_data);
?>