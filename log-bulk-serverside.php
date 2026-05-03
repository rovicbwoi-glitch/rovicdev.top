<?php

chkSession();
if($user_id_2 == 1 || $user_level_2 == 'superadmin' || $user_level_2 == 'reseller'){
	
}else{
	header("Location: /dashboard");	
}


$requestData= $_REQUEST;
$type = $_GET['type'];
if(empty($requestData)){
	$db->RedirectToURL($db->base_url());
	exit;	
}

$columns = array( 
    0	=> 'regdate'
);

if($user_id_2 == 1 || $user_level_2 == 'superadmin'){
    $sql = "SELECT * FROM users WHERE 1=1 AND user_level='bulk' AND user_group>0 GROUP BY user_group";
}else{
    $sql = "SELECT * FROM users WHERE 1=1 AND user_level='bulk' AND user_group>0 AND upline='$user_id_2' GROUP BY user_group";
}

if( !empty($requestData['search']['value']) ) { 
	$sql.=" AND ( regdate LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = $db->sql_query($sql) or die();
$totalFiltered = $db->sql_numrows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

$query = $db->sql_query($sql) or die();

$data = array();
while( $row = $db->sql_fetchrow($query) ) {
	$nestedData=array();
	$regdate = $row['regdate'];
	$usergroup = $row['user_group'];
	$userupline = $row['upline'];
	$userprefix = $row['username_prefix'];
	
	if($userprefix == ''){
	    $prefix = 'null';
	}else{
	    $prefix = $userprefix;
	}
	
	$total_qry = $db->sql_query("SELECT user_name FROM users WHERE user_group='$usergroup'");
	$totaluser = $db->sql_numrows($total_qry);
	
	$upline_qry = $db->sql_query("SELECT user_name FROM users WHERE user_id='$userupline'");
    $upline_row = $db->sql_fetchrow($upline_qry);
    $upline = $upline_row['user_name'];
	
	$nestedData[] = '<span class="badge badge-primary">'.$regdate.'</span>';
	$nestedData[] = '<span class="badge badge-info">'.$upline.'</span>';
	$nestedData[] = '<span class="badge badge-warning">'.$prefix.'</span>';
	$nestedData[] = '<span class="badge badge-success">'.$totaluser.'</span>';
	$nestedData[] = '<a class="btn btn-outline-primary btn-sm" href="/serverside/export/bulk.php?exportmode=csv&amp;ugroup='.$usergroup.'&rdate='.$regdate.'" target="_blank"><i class="fas fa-file-download"></i> CSV</a>
	                <a class="btn btn-outline-primary btn-sm" href="/serverside/export/bulk.php?exportmode=txt&amp;ugroup='.$usergroup.'&rdate='.$regdate.'" target="_blank"><i class="fas fa-file-alt"></i> TXT</a>
	                <button type="button" class="btn btn-sm btn-outline-primary btn-delete-bulk" data-prefix="'.$prefix.'" data-group="'.$usergroup.'"><i class="far fa-trash-alt"></i> DELETE</button>';

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