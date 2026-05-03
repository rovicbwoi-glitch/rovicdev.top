<?php
$read_cookie = explode("|", $db->decrypt_key($user));
$db->sql_query("UPDATE users SET login_status='offline' WHERE user_name='$read_cookie[1]' AND user_pass='$read_cookie[2]'");
setcookie("user", NULL, time()-3600, "/"); 
unset($_COOKIE['user']);
$user = "";
unset($user);
chkSession();
?>