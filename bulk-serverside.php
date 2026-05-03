<?php

chkSession();
if($user_id_2 == 1 || $user_level_2 == 'superadmin' || $user_level_2 == 'reseller'){
	
}else{
	header("Location: /dashboard");	
}

$requestData= $_REQUEST;
if(empty($requestData)){
	$db->RedirectToURL($db->base_url());
	exit;	
}

$columns = array( 
    0	=> 'user_id',
	1	=> 'user_name',
	2	=> null
);

$sql = "SELECT * FROM users";
$query = $db->sql_query($sql) or die();
$totalData = $db->sql_numrows($query);
$totalFiltered = $totalData;
if($user_id_2 == 1 || $user_level_2 == 'superadmin'){
    $sql = "SELECT * FROM users WHERE 1=1 AND user_id!='$user_id_2' AND user_level='bulk'";
}else{
	$sql = "SELECT * FROM users WHERE 1=1 AND user_id!='$user_id_2' AND upline='$user_id_2' AND user_level='bulk'";
}

if( !empty($requestData['search']['value']) ) { 
	$sql.=" AND ( user_id LIKE '%".$requestData['search']['value']."%' "; 
	$sql.=" OR user_name LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = $db->sql_query($sql) or die();
$totalFiltered = $db->sql_numrows($query);
$sql.="ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']." ";

$query = $db->sql_query($sql) or die();


$data = array();
while( $row = $db->sql_fetchrow($query) ) {
	$nestedData=array();
	$userid = $row['user_id'];
	$username = $row['user_name'];
	$password = $row['user_pass'];
	$user_pass = $db->decrypt_key($password);
	$userpass = $db->encryptor('decrypt', $user_pass);
	$userduration = $row['duration'];
	$stat = $row['device_connected'];
	$is_online = $row['is_connected'];
	$active_date = $row['active_date'];
	
	$bandwidth_qry = $db->sql_query("SELECT sum(bytes_sent) as bsent, sum(bytes_received) as breceived FROM bandwidth_logs WHERE username='$username'");
	$bandwidth_row = $db->sql_fetchrow($bandwidth_qry);
	
	$bytes_sent = $bandwidth_row['bsent'];
	$bytes_received = $bandwidth_row['breceived'];
	
	if($bytes_sent == 0 && $bytes_received == 0){
	    $bandwidth_used = '0B';
	}else{
    	$bandwidth = $bytes_sent + $bytes_received;
    	$bandwidth_used = convertToReadableSize($bandwidth);
	}
	
	$dur = $db->calc_time($userduration);	
	$pdays = $dur['days'] . " days";
	$phours = $dur['hours'] . " hours";
	$pminutes = $dur['minutes'] . " minutes";
	$pseconds = $dur['seconds'] . " seconds";
	
	$elapse = get_time_elapsed("$active_date");
	
	if($userduration == 0){
	    $duration = '<span class="badge badge-danger"><span class="fas fa-clock"></span> Expired</span>';
	}else{
		$duration = strtotime($pdays . $phours . $pminutes . $pseconds);
		$duration = '<span class="badge badge-primary"><span class="far fa-calendar-alt"></span> '.date('Y-m-d H:m:s', $duration).'</span>';
	}	
	
	if($stat == 0){
	    $expired = '<span class="badge badge-primary"><span class="far fa-calendar-alt"></span> none</span>';
	}else{
	    $expired = $duration;
	}
	
	if($is_online == 0){
	    $status = '<span class="badge badge-danger"><i class="fa fa-times-circle"></i> No</span>';
	}else{
	    $status = '<span class="badge badge-success"><i class="fa fa-check-circle"></i> Yes</span>';
	}
	
	if($active_date == '0000-00-00 00:00:00'){
	    $session = '<span class="badge badge-info"><i class="far fa-clock"></i> none</span>';
	}else{
	    $session = '<span class="badge badge-info"><i class="far fa-clock"></i> '.$elapse.'</span>';
	}
	
	$nestedData[] = '<span class="badge badge-primary">'.$username.'</span>';
	$nestedData[] = $status;
	$nestedData[] = $session;
	$nestedData[] = '<span class="badge badge-info"><i class="fas fa-tachometer-alt"></i> '.$bandwidth_used.'</span>';
	$nestedData[] = $expired;
	$nestedData[] = '<div class="btn-group btn-group-md" role="group">
                    	<button type="button" class="btn btn-outline-primary" onclick="view_info('.$userid.')"><i class="far fa-eye"></i></button>
                    	<button type="button" class="btn btn-outline-primary" onclick="user_option('.$userid.')"><i class="fas fa-edit"></i></button>
                    	<button type="button" class="btn btn-outline-success btn-copy" data-clipboard-text="User Details 

Username : '.$username.' 
Password : '.$userpass.'" data-id="'.$userid.'"><i class="far fa-copy"></i></button>
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