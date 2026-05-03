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
    0	=> 'title',
	1	=> 'type'
);

$sql = "SELECT * FROM notification WHERE 1=1";

if( !empty($requestData['search']['value']) ) { 
	$sql.=" AND ( title LIKE '%".$requestData['search']['value']."%' "; 
	$sql.=" OR type LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = $db->sql_query($sql) or die();
$totalFiltered = $db->sql_numrows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']." ";

$query = $db->sql_query($sql) or die();


$data = array();
while( $row = $db->sql_fetchrow($query) ) {
	$nestedData=array();
	$nid = $row['id'];
	$title = $row['title'];
	$type_ = $row['type'];
	$filename = $row['filename'];
	$date = $row['date'];
	
	if($type_ == 1){
	    $type = 'INFO';
	}elseif($type_ == 2){
	    $type = 'EMERGENCY';
	}elseif($type_ == 3){
	    $type = 'CRITICAL';
	}
	
	$nestedData[] = '<figure class="avatar avatar-sm"><img src="/dist/img/tag.png" alt="nameicon"></figure><strong> '.strtoupper($title).'</strong>';
	$nestedData[] = '<figure class="avatar avatar-sm"><img src="/dist/img/info.png" alt="nameicon"></figure><strong> '.$type.'</strong>';
	$nestedData[] = '<figure class="avatar avatar-sm"><img src="/dist/img/calendar.png" alt="nameicon"></figure><strong> '.$date.'</strong>';
	$nestedData[] = '<div class="btn-group btn-group-md" role="group">
                    	<button type="button" class="btn btn-outline-primary btn-notification-view" data-id="'.$nid.'"><i class="far fa-eye"></i></button>
                    	<button type="button" class="btn btn-outline-danger btn-notification-delete" data-id="'.$nid.'"><i class="fas fa-trash-alt"></i></button>
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