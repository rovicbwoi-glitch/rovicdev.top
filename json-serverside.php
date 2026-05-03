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
    0	=> 'name',
	1	=> 'encryption'
);

$sql = "SELECT * FROM json_update WHERE 1=1";

if( !empty($requestData['search']['value']) ) { 
	$sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' "; 
	$sql.=" OR encryption LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = $db->sql_query($sql) or die();
$totalFiltered = $db->sql_numrows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']." ";

$query = $db->sql_query($sql) or die();


$data = array();
while( $row = $db->sql_fetchrow($query) ) {
	$nestedData=array();
	$jid = $row['id'];
	$name = $row['name'];
	$type_ = $row['type'];
	$encryption = $row['encryption'];
	
	if($type_ == 1){
	    $type = 'OPENVPN';
	}elseif($type_ == 2){
	    $type = 'OPENCONNECT';
	}elseif($type_ == 3){
	    $type = 'SSH';
	}elseif($type_ == 4){
	    $type = 'PPTP';
	}
	
	$nestedData[] = '<figure class="avatar avatar-sm"><img src="/dist/img/programming.png" alt="nameicon"></figure><strong> '.strtoupper($name).'</strong>';
	$nestedData[] = '<figure class="avatar avatar-sm"><img src="/dist/img/code.png" alt="nameicon"></figure><strong> '.$type.'</strong>';
	$nestedData[] = '<figure class="avatar avatar-sm"><img src="/dist/img/lock.png" alt="nameicon"></figure><strong> '.strtoupper($encryption).'</strong>';
	$nestedData[] = '<div class="btn-group btn-group-md" role="group">
                    	<button type="button" class="btn btn-outline-primary btn-json-view" data-hash="'.$encryption.'"><i class="far fa-eye"></i></button>
                    	<button type="button" class="btn btn-outline-primary btn-json-edit" data-hash="'.$encryption.'" data-name="'.$name.'"><i class="fas fa-edit"></i></button>
                    	<button type="button" class="btn btn-outline-danger btn-json-delete" data-hash="'.$encryption.'"><i class="fas fa-trash-alt"></i></button>
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