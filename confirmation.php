<?php
if(is_logged_in($user)) {
	header("Location: /dashboard");
}else{
    
    $url = $_SERVER['REQUEST_URI'];
    
    $url_components = parse_url($url);
    parse_str($url_components['query'], $params);
    $id = $params['id'];
    $uid = "'$id'";
    
    if(empty($id)){
        header("Location: /login");
    }else{
    
        $qry = $db->sql_query("SELECT login_note, maintenance_status FROM site_options WHERE id='1'") OR die();
        $row = $db->sql_fetchrow($qry);
        $maintenance = $row['maintenance_status'];
        
        $qry2 = $db->sql_query("SELECT user_2fa_id, user_2fa_duration FROM users WHERE user_2fa_id='$id'") OR die();
        $row2 = $db->sql_fetchrow($qry2);
        
        if($id == $row2['user_2fa_id']){
            
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
            
            $dur = $db->calc_time($row2['user_2fa_duration']);	
        	$pminutes = $dur['minutes'] . " minutes";
        	$pseconds = $dur['seconds'] . " seconds";
            
            if($row2['user_2fa_duration'] > 0){
                $otp_duration = "Request again after " . $dur['minutes'] . " minutes";
            }else{
                $otp_duration = '<a href="javascript:void(0);" onclick="rsnd('.$uid.')">Resend confirmation code.</a>';
            }
            
            $spam = $db->encryptor('encrypt', 'try to hack');
            $spam = $db->encryptor('encrypt', $spam);
            $smarty->assign('code', $spam);
            $smarty->assign('mainte', $mainte);
            $smarty->assign('otpduration', $otp_duration);
            $smarty->display("confirmation.tpl");
        }else{
            header("Location: /login");
        }
    }
}
?>