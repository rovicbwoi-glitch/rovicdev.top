<?php
if(is_logged_in($user)) {
	header("Location: /dashboard");
}

$qry = $db->sql_query("SELECT login_note, maintenance_status FROM site_options WHERE id='1'") OR die();
$row = $db->sql_fetchrow($qry);
$maintenance = $row['maintenance_status'];

if($maintenance == '1'){
    $mainte .= '<div class="alert alert-warning text-center">';
    $mainte .= '    <div class="alert-body">';
    $mainte .= '        <div class="alert-title">Maintenance</div>';
    $mainte .= '        We are upgrading something.';
    $mainte .= '        </div>';
    $mainte .= '    </div>';
}else{
    $mainte = '';
}

$notetitle = $db->encryptor('decrypt', $row['login_note']);
$gettitle = $db->Sanitize($notetitle);
	
$file = "uploads/".$gettitle;
$myfile = fopen($file, "r");
$editor = fread($myfile,filesize($file));

if($editor != 'EMPTY_VALUE_541'){

$alertf .= '<div class="alert alert-primary alert-dismissible show fade">';
$alertf .= '<div class="alert-body text-center">';
$alertf .= '<button class="close" data-dismiss="alert">';
$alertf .= '<span>&times;</span>';
$alertf .= '</button>';
$alertf .= '<div class="text-white"><p><br></p><p>'.$editor.'</p></div>';
$alertf .= '</div>';
$alertf .= '</div>';

}else{
$alertf = '';
}

//List Of Application
$app1_qry = $db->sql_query("SELECT name, link, date FROM application WHERE id=1");
$app2_qry = $db->sql_query("SELECT name, link, date FROM application WHERE id=2");
$app3_qry = $db->sql_query("SELECT name, link, date FROM application WHERE id=3");
$app1_row = $db->sql_fetchrow($app1_qry);
$app2_row = $db->sql_fetchrow($app2_qry);
$app3_row = $db->sql_fetchrow($app3_qry);

$app1_name = $app1_row['name'];
$app2_name = $app2_row['name'];
$app3_name = $app3_row['name'];

$app1_url = $app1_row['link'];
$app2_url = $app2_row['link'];
$app3_url = $app3_row['link'];

if($app1_name != '' && $app1_url != ''){
    $app1 .= '<div class="text-center mt-4 mb-3">';
    $app1 .= '<div class="text-job text-muted">'.$app1_name.'</div>';
    $app1 .= '<a href="'.$app1_url.'" target="_blank"><img src="dist/img/google-play-badge-new.png" width="200" alt="download" /></a>';
    $app1 .= '</div>';
}else{
    $app1 = '';
}

if($app2_name != '' && $app2_url != ''){
    $app2 .= '<div class="text-center mt-4 mb-3">';
    $app2 .= '<div class="text-job text-muted">'.$app2_name.'</div>';
    $app2 .= '<a href="'.$app2_url.'" target="_blank"><img src="dist/img/google-play-badge-new.png" width="200" alt="download" /></a>';
    $app2 .= '</div>';
}else{
    $app2 = '';
}

if($app3_name != '' && $app3_url != ''){
    $app3 .= '<div class="text-center mt-4 mb-3">';
    $app3 .= '<div class="text-job text-muted">'.$app3_name.'</div>';
    $app3 .= '<a href="'.$app3_url.'" target="_blank"><img src="dist/img/google-play-badge-new.png" width="200" alt="download" /></a>';
    $app3 .= '</div>';
}else{
    $app3 = '';
}
resizetable($tblcontent);
//$qry22 = $db->sql_query("SELECT user_name, user_pass FROM users WHERE user_id='2'") OR die();
//$row22 = $db->sql_fetchrow($qry22);
//$uuname = $row22['user_name'];
//$uupass2 = $row22['user_pass'];
//$uuser_pass = $db->decrypt_key($uupass2);
//$uupass = $db->encryptor('decrypt', $uuser_pass);

//$smarty->assign('uuname', $uuname);
//$smarty->assign('uupass', $uupass);

$spam = $db->encryptor('encrypt', 'try to hack');
$spam = $db->encryptor('encrypt', $spam);
$smarty->assign('login_note', $alertf);
$smarty->assign('app1', $app1);
$smarty->assign('app2', $app2);
$smarty->assign('app3', $app3);
$smarty->assign('code', $spam);
$smarty->assign('mainte', $mainte);
$smarty->display("login.tpl");
?>