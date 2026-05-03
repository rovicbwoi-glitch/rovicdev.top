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
    0	=> 'host_name',
	1	=> 'domain_name',
	2   => 'ip_address'
);

$sql = "SELECT * FROM dns WHERE 1=1";

if( !empty($requestData['search']['value']) ) { 
	$sql.=" AND ( host_name LIKE '%".$requestData['search']['value']."%' "; 
	$sql.=" OR domain_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR ip_address LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = $db->sql_query($sql) or die();
$totalFiltered = $db->sql_numrows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']." ";

$query = $db->sql_query($sql) or die();


$data = array();
while( $row = $db->sql_fetchrow($query) ) {
	$nestedData=array();
	$dns_id = $row['dns_id'];
	$record_id = $row['record_id'];
	$host_name = $row['host_name'];
	$cf_domain = $row['domain_name'];
	$cloudflaredomain = $db->decrypt_key2($cf_domain);
	$domain_name = $db->encryptor2('decrypt', $cloudflaredomain);
	$ip_address = $row['ip_address'];
	$host = $host_name.'.'.$domain_name;
	
	$nestedData[] = '<figure class="avatar avatar-sm"><img src="/dist/img/cloudflare.png" alt="nameicon"></figure><strong> '.$host_name.'.'.$domain_name.'</strong>';
	$nestedData[] = '<figure class="avatar avatar-sm"><img src="/dist/img/ip.png" alt="nameicon"></figure><strong> '.$ip_address.'</strong>';
	$nestedData[] = '<div class="btn-group btn-group-md" role="group">
                    	<button type="button" class="btn btn-outline-primary btn-dns-update" data-identifier="'.$record_id.'" data-hostname="'.$host.'"><i class="fas fa-edit"></i></button>
                    	<button type="button" class="btn btn-outline-danger btn-dns-delete" data-identifier="'.$record_id.'" data-hostname="'.$host.'"><i class="fas fa-trash-alt"></i></button>
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