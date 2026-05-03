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
    0	=> 'id',
	1	=> 'user_id'
);

$sql = "SELECT * FROM deleted_logs";
$query = $db->sql_query($sql) or die();
$totalData = $db->sql_numrows($query);
$totalFiltered = $totalData;
if($user_id_2 == 1 || $user_level_2 == 'superadmin'){
	$sql = "SELECT * FROM deleted_logs WHERE 1=1";
}else{
    $sql = "SELECT * FROM deleted_logs WHERE 1=1 AND user_upline='$user_id_2'";
}

if( !empty($requestData['search']['value']) ) { 
	$sql.=" AND ( id LIKE '%".$requestData['search']['value']."%' "; 
	$sql.=" OR user_id LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = $db->sql_query($sql) or die();
$totalFiltered = $db->sql_numrows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

$query = $db->sql_query($sql) or die();


$data = array();
while( $row = $db->sql_fetchrow($query) ) {
	$nestedData=array();
	$id = $row['id'];
	$user_id = $row['user_id'];
	$user_upline = $row['user_upline'];
	$date = $row['date'];
	$user_level = $row['user_level'];
	
	$user_qry = $db->sql_query("SELECT user_name FROM users_delete WHERE user_id='$user_id'") OR die();
	$user_row = $db->sql_fetchrow($user_qry);
	$user_ = $user_row['user_name'];
	
	$upline_qry = $db->sql_query("SELECT user_name FROM users WHERE user_id='$user_upline'") OR die();
	$upline_row = $db->sql_fetchrow($upline_qry);
	$upline_ = $upline_row['user_name'];
	
	$nestedData[] = '<span class="badge badge-primary">'.$date.'</span>';;
	$nestedData[] = $user_;
	$nestedData[] = $upline_;
	$nestedData[] = $user_level;

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