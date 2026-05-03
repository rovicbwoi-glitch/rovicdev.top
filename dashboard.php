<?php
chkSession();

#Reseller
$reseller = $db->sql_query("SELECT user_name FROM users WHERE is_groupname='reseller' AND user_id!='".$user_id_2."'");
$reseller = $db->sql_numrows($reseller);
$smarty->assign("reseller", $reseller);

$reseller_ = $db->sql_query("SELECT user_name FROM users WHERE is_groupname='reseller' AND user_id!='".$user_id_2."' AND upline='".$user_id_2."'");
$reseller_ = $db->sql_numrows($reseller_);
$smarty->assign("reseller_", $reseller_);

$read_cookie = explode("|", $db->decrypt_key($user));
$userdata = $db->sql_query("SELECT * FROM users WHERE user_name='$read_cookie[1]' AND user_pass='$read_cookie[2]'");
$row = $db->sql_fetchrow($userdata);

resizetable($tblcontent);

//List Of Notices
$query = $db->sql_query("SELECT * FROM download ORDER BY download_date DESC");
while($row = $db->sql_fetchrow($query)){
	$id = $row['id'];
	$title = nl2br($row['download_title']);
	$msg = nl2br($row['download_msg']);
	$network = $row['download_network'];
	$dt = date("F d, Y h:i:s", strtotime($row['download_date']));
	$file = $db->base_url() . '_uploads/'.$row['download_file'];
	if($row['download_file'] == ""){
		$DLfiles = "";
	}else{
		$DLfiles = "<a class='text-white' href='".$file."'>Click Here to DOWNLOAD</a>";
	}
	
	if($row['download_network'] == 'NOTICE'){
	    $ico = 'icon-success';
	    $ttl = 'text-success';
	}else
	if($row['download_network'] == 'UPDATE'){
	    $ico = 'icon-primary';
	    $ttl = 'text-primary';
	}
	
	if($row['download_device'] == 'ANDROID'){
	    $icon = 'mdi mdi-android';
	}else
	if($row['download_device'] == 'IOS'){
	    $icon = 'mdi mdi-apple';
	}else
	if($row['download_device'] == 'WINDOWS'){
	    $icon = 'mdi mdi-windows';
	}else
	if($row['download_device'] == 'CONFIG'){
	    $icon = 'mdi mdi-folder-network-outline';
	}else
	if($row['download_device'] == 'OTHERS'){
	    $icon = 'mdi mdi-shield-check';
	}
	
	$download[]  = '<i class="'.$icon.' '.$ico.'"></i>';
	$download[] .= '<div class="time-item">';
	$download[] .= '<div class="item-info">';
	$download[] .= '<div class="d-flex justify-content-between align-items-center">';
	$download[] .= '<h6 class="m-0 '.$ttl.'">'.$title.'</h6>';
	$download[] .= '<span class="text-muted">'.$dt.'</span>';
	$download[] .= '</div>';
	
	$download[] .= '<p class="text-muted mt-3">';
	$download[] .= ''.$msg.'';
	$download[] .= '</p>';
	
    $download[] .= '<div>';
    $download[] .= '<span class="badge badge-soft-secondary text-secondary">'.$DLfiles.'</span>';
    $download[] .= '</div>';
    $download[] .= '</div>';
    $download[] .= '</div>';
    
    
    $download2[]  = '<div class="d-flex justify-content-between align-items-start align-items-sm-center mb-4 flex-column flex-sm-row">';
    $download2[]  = '<div class="left d-flex ">';
    $download2[]  = '<div class="icon icon-lg shadow mr-3 text-gray"><i class="fab fa-dropbox"></i></div>';
    $download2[]  = '<div class="text">';
    $download2[]  = '<h6 class="mb-0 d-flex align-items-center"> <span>'.$title.'</span>&nbsp<span class="small">['.$dt.']</span></h6><small class="text-gray">'.$msg.'</small>';
    $download2[]  = '<p>';
    $download2[] .= '<span class="badge badge-primary">'.$DLfiles.'</span>';
    $download2[] .= '</p>';
    $download2[]  = '</div>';
    $download2[]  = '</div>';
    $download2[]  = '</div>';
    
	$smarty->assign('download', $download);	
	$smarty->assign('download2', $download2);
}

$qry = $db->sql_query("SELECT maintenance_status FROM site_options WHERE id='1'") OR die();
$row = $db->sql_fetchrow($qry);
$maintenance = $row['maintenance_status'];

if($maintenance == '1'){
    $mainte .= '<div class="alert alert-warning" role="alert">';
    $mainte .= '    <marquee>';
    $mainte .= '        <strong><i class="fas fa-bullhorn"></i> NOTICE</strong> : We are conducting a maintenance we&#39;re doing our best to improve our service, vpn connections might be interrupted at any moment please inform your clients. Thank you ! </marquee>';
    $mainte .= '</div>';
}else{
    $mainte = '';
}

$smarty->assign('mainte', $mainte);
$smarty->display("dashboard.tpl");
?>