<?php

chkSession();
if($user_id_2 == 1 || $user_level_2 == 'superadmin' || $user_level_2 == 'reseller'){
	
}else{
	header("Location: /dashboard");	
}

if(!isset($_GET['type']) || empty($_GET['type'])){
	echo '<script>alert("Error");</script>';
	$db->RedirectToURL($db->base_url());
	exit;	
}else{
$requestData= $_REQUEST;
$type = $_GET['type'];
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
    $sql = "SELECT * FROM users WHERE 1=1 AND user_id!='$user_id_2' AND user_level='$type'";
}else{
	$sql = "SELECT * FROM users WHERE 1=1 AND user_id!='$user_id_2' AND upline='$user_id_2' AND user_level='$type'";
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
	
	$nestedData[] = $username;
	$nestedData[] = $username;
	$nestedData[] = $username;
	$nestedData[] = $username;
	$nestedData[] = $username;

	$data[] = $nestedData;	
}

$json_data = array(
			"draw"            => intval( $requestData['draw'] )? intval( $_REQUEST['draw'] ) : 0,
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => ($data )
			);

echo json_encode($json_data);
}
?>