<?php
//skip the functions file if somebody call it directly from the browser.
if (preg_match("/config.php/i", $_SERVER['SCRIPT_NAME'])) {
    Header("Location: /"); die();
}

require 'smarty/Smarty.class.php';

$smarty = new Smarty;

include 'db_config.php';
require "mysql.class.php";
$db = new mysql_db();
$db->InitDB($DB_host,$DB_user,$DB_pass,$DB_name);


$bak_query = $db->sql_query("SELECT name, owner, description, bak_to, bak_cc FROM site_options WHERE id='1'");
$bak_row = $db->sql_fetchrow($bak_query);
$bak_to = $bak_row['bak_to'];
$bak_cc = $bak_row['bak_cc'];
$bak_sitename = $bak_row['name'];
$bak_siteowner = $bak_row['owner'];
$bak_sitedesc = $bak_row['description'];

$db->SetWebsiteName($bak_sitename);
$db->SetWebsiteTitle($bak_sitedesc);
$db->SetBakTo($bak_to);
$db->SetBakCC($bak_cc);

$ua = $db->getBrowser();
$browser = "" . $ua['name'] . " " . $ua['version'] . "" ; 
$ipadd = "" . $db->get_client_ip() . ""; 
$base_url = $db->base_url();
$smarty->assign('getIP', $ipadd);
$smarty->assign('getBrowser', $browser);
$smarty->assign('base_url', $db->base_url());
$smarty->assign('GetSelfScript', $db->GetSelfScript());
$smarty->assign('siteTitle', $db->siteTitle);
$smarty->assign('sitename', $db->sitename);

$date = new DateTime();
$current_timestamp = $date->getTimestamp();
$smarty->assign('current_timestamp', $current_timestamp);

$premium_encrypt = $db->encryptor('encrypt', 'premium');
$smarty->assign("premium_encrypt", $premium_encrypt);

$vip_encrypt = $db->encryptor('encrypt', 'vip');
$smarty->assign("vip_encrypt", $vip_encrypt);

$private_encrypt = $db->encryptor('encrypt', 'private');
$smarty->assign("private_encrypt", $private_encrypt);

$add_encrypt = $db->encryptor('encrypt', 'add');
$smarty->assign("add_encrypt", $add_encrypt);

$substract_encrypt = $db->encryptor('encrypt', 'substract');
$smarty->assign("substract_encrypt", $substract_encrypt);

$login_encrypt = $db->encryptor('encrypt', 'Login Account');
$smarty->assign("login_encrypt", $login_encrypt);

$unfreeze_encrypt = $db->encryptor('encrypt', 'Unfreeze Account');
$smarty->assign("unfreeze_encrypt", $unfreeze_encrypt);

$firenet_encrypt = $db->encryptor('encrypt', 'firenetdev');
$smarty->assign("firenet_encrypt", $firenet_encrypt);

for($i=0; $i<366; $i++)
{
	$encrypt_days .= '<option value="'.base64_encode($db->encrypt_key($db->encrypt_key($i))).'">';
	$encrypt_days .= $i;
	$encrypt_days .= '</option>';
	$smarty->assign("encrypt_days", $encrypt_days);
}

for($i=0; $i<25; $i++)
{
	$encrypt_hours .= '<option value="'.urlencode($db->encrypt_key($db->encrypt_key($i))).'">';
	$encrypt_hours .= $i;
	$encrypt_hours .= '</option>';
	$smarty->assign("encrypt_hours", $encrypt_hours);
}

$dns_list_array=array(
			1=>array("rawter.xyz","9fa7cb0f21cb6feec656d3472167cdb4","4db1ac09d6229a35a5758a6e6b4c6df3cf8e0","azimaxus@gmail.com")
	);

for($row = 1;$row < 101; $row++){
		if(!empty($dns_list_array[$row][0])){
		$domain_list .= '<option value="'.$dns_list_array[$row][0].'">';
		$domain_list .= $dns_list_array[$row][0];
		$domain_list .= '</option>';
		} else {
			break;
		}
	}
	
$smarty->assign('domain_list', $domain_list);
$smarty->assign('dns_list', $dns_list_array);

$dom_encrypt = $db->encrypt_key($db->encryptor('encrypt','https://linancetonotice.dxplus.top/api/check.php?'));
$smarty->assign('lenzpogi', $dom_encrypt);

$year_now = date('Y');
$smarty->assign("year_now", $year_now);
?>