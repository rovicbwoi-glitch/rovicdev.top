<?php
chkSession();
if($user_id_2 == 2 || $user_level_2 == 'developer'){
	
}else{
	header("Location: /dashboard");	
}

$smarty->display("developer-settings.tpl");
?>