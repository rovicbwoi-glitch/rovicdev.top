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
    $sql = "SELECT * FROM users WHERE 1=1 AND user_id!='$user_id_2' AND user_level='reseller'";
}else{
	$sql = "SELECT * FROM users WHERE 1=1 AND user_id!='$user_id_2' AND upline='$user_id_2' AND user_level='reseller'";
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
	$usercredits = $row['credits'];
	$is_freeze = $row['is_freeze'];
	
	if($is_freeze == 0){
	    $is_blocked = '<span class="badge badge-success"><span class="fas fa-user"></span> No</span>';
	}else{
	    $is_blocked = '<span class="badge badge-danger"><span class="fas fa-user-slash"></span> Yes</span>';
	}
	
	$nestedData[] = '<span class="badge badge-primary">'.$username.'</span>';
	$nestedData[] = '<span class="badge badge-primary"><i class="fas fa-coins"></i> '.$usercredits.'</span>';
	$nestedData[] = $is_blocked;
	$nestedData[] = '<div class="btn-group btn-group-md" role="group">
                    	<button type="button" class="btn btn-outline-primary" onclick="view_info('.$userid.')"><i class="far fa-eye"></i></button>
                    	<button type="button" class="btn btn-outline-primary" onclick="user_option('.$userid.')"><i class="fas fa-edit"></i></button>
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