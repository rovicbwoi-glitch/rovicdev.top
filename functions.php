<?php
define('DOC_ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/');
include DOC_ROOT_PATH . './includes/config.php';
require_once DOC_ROOT_PATH . './includes/Mobile_Detect.php';
$detect = new Mobile_Detect;
if(isset($_COOKIE['user'])) {
	$user =  $_COOKIE['user'];
}

if(isset($user)){
	$user = $db->decrypt_key($user);
	$user = addslashes($user);
	$user = $db->encrypt_key($user);
}

function is_logged_in($user) {
	global $user, $db;
	$read_cookie = explode("|", $db->decrypt_key($user));
	$result = $db->sql_query("SELECT user_name FROM users WHERE user_name='$read_cookie[1]' AND user_pass='$read_cookie[2]'");
	$num_row = $db->sql_numrows($result);
	if($num_row > 0) {
		return 1;
	}
	return 0;
}
global $user, $db;
$read_cookie_2 = explode("|", $db->decrypt_key($user));
$user_id_2 = $db->Sanitize($read_cookie_2[0]);

setcookie("user_name", $read_cookie_2[1], time()+3600, "/");

$result_2 = $db->sql_query("SELECT credits, 
								   code,
								   ss_id,
								   vip_duration,
								   private_duration,
								   private_control,
								   duration, 
								   user_level,
								   lastlogin,
								   full_name,
								   user_pass,
								   user_email,
								   user_name,
								   upline,
								   is_groupname
							FROM users WHERE user_id='$user_id_2'");
$legal_name = 'Core4VPN';
$row_2 = $db->sql_fetchrow($result_2);
$ss_id_2 = $row_2['ss_id'];
$code_2 = $row_2['code'];
$user_level_2 = $row_2['user_level'];
$credits_2 = $row_2['credits'];
$upline_2 = $row_2['upline'];
$auth_2 = $row_2['user_pass'];
$duration_2 = $row_2['duration'];
$vip_duration_2 = $row_2['vip_duration'];
$private_duration_2 = $row_2['private_duration'];
$private_control_2 = $row_2['private_control'];
$full_name_2 = $row_2['full_name'];
$user_name_2 = $row_2['user_name'];
$user_email_2 = $row_2['user_email'];
$datatable1 = 'jpafZdCmo7eFm7O8k36';
$datatable2 = 'uYb6ptuWbhtW7qJqH0t';
$datatable3 = 'S7s8+zz97InaizpKesn';
$datatable4 = 'quYrp22u72biYfHobvC';
$is_groupname_2 = $row_2['is_groupname'];
$lastlogin = date('F d, Y h:i', strtotime($row_2['lastlogin']));
$smarty->assign("ss_id_2", $ss_id_2);
$smarty->assign("code_2", $code_2);
$smarty->assign("user_name_2", $user_name_2);
$smarty->assign("user_email", $user_email_2);
$smarty->assign("full_name_2", $full_name_2);
$smarty->assign("lastlogin", $lastlogin);
$smarty->assign("user_id_2", $user_id_2);
$smarty->assign("user_level_2", $user_level_2);
$smarty->assign("credits_2", $credits_2);
$smarty->assign("duration_2", $duration_2);
$smarty->assign('vip_duration_2', $vip_duration_2);
$smarty->assign('private_duration_2', $private_duration_2);
$smarty->assign('private_control_2', $private_control_2);
$smarty->assign("auth_2", $auth_2);
$smarty->assign("upline_2", $upline_2);
$smarty->assign("is_groupname_2", $is_groupname_2);
$smarty->assign("encrypt_user_id", $db->encryptor('encrypt',$user_id_2));
$smarty->assign("encrypt_dur", $db->encryptor('encrypt',$duration_2));
$smarty->assign("encrypt_vip", $db->encryptor('encrypt',$vip_duration_2));

$secret = $db->encryptor('encrypt',$user_id_2);
$secret = urlencode($secret);
$smarty->assign("secret", $secret);

if($user_level_2 == 'superadmin' || $user_level_2 == 'developer'){
    $creditx = '&#8734';
}else{
    $creditx = $credits_2;
}
$smarty->assign("creditx_2", $creditx);

$profile_query = $db->sql_query("SELECT profile_image, first_name, last_name FROM users_profile WHERE profile_id='$user_id_2'");
$profile_row = $db->sql_fetchrow($profile_query);
$profile_image = $profile_row['profile_image'];
$fname = $profile_row['first_name'];
$lname = $profile_row['last_name'];
$datab1 = $datatable1.$datatable2;
$datab2 = $datatable3.$datatable4;
$default = 'profile/avatar-1.png';
$profile = 'profile/'.$user_id_2.'/'.$profile_image;

$smarty->assign("first_name", $fname);
$smarty->assign("last_name", $lname);
$smarty->assign("full_name", $fname.' '.$lname);

$upline_query = $db->sql_query("SELECT profile_image, first_name, last_name FROM users_profile WHERE profile_id='$upline_2'");
$upline_row = $db->sql_fetchrow($upline_query);
$upline_image = $upline_row['profile_image'];
$upline_default = 'profile/avatar-1.png';
$upline_profile = 'profile/'.$upline_2.'/'.$upline_image;

$site_query = $db->sql_query("SELECT * FROM site_options WHERE id='1'");
$site_row = $db->sql_fetchrow($site_query);
$site_image = 'uploads/images/'.$site_row['logo'];
$site_name = $site_row['name'];
$site_theme = $site_row['theme'];
$site_update = $site_row['update_json'];
$site_license = $site_row['license'];
$site_logo_status = $site_row['logo_status'];
$site_loginnote = $site_row['login_note'];
$site_description = $site_row['description'];
$sitelogo_default = 'dist/img/main/logo.png';
$tabledata1 = 'uqZ8pNGehpmiuo/LuHjP0bt9wdOmj';
$tabledata2 = '2O0kn+kzLnXh82yoNCYhqa9voGH5s';
$tabledata3 = '2qvMCSip6nwIqirqaCi52dn5Kl';
$sitelogo = $site_image;

$sitedns_domain = $site_row['dns_domain'];
$sitedns_global = $site_row['dns_global'];
$sitedns_zone = $site_row['dns_zone'];
$sitedns_email = $site_row['dns_email'];

if($site_image === ''){
	$site_logo = $sitelogo_default;
}else{
	$site_logo = $sitelogo;
}

if($site_logo_status == 1){
    $slogo = '<img alt="image" src="'.$site_logo.'" style="width:3.5rem;"><br>';
}else{
    $slogo = '';
}

$tbdata = $tabledata1.$tabledata2.$tabledata3;
$smarty->assign("site_note", $site_loginnote);
$smarty->assign("site_logo", $site_logo);
$smarty->assign("s_logo", $slogo);
$smarty->assign("site_name", $site_name);
$smarty->assign("site_theme", $site_theme);
$smarty->assign("site_description", $site_description);


if($user_level_2 == 'subreseller'){
	$rank = 'SubReseller';
	$rank2 ='<i class="fa fa-star"></i>';
}elseif($user_level_2 == 'reseller'){
	$rank = 'Reseller';
	$rank2 ='<i class="fa fa-star"></i>
	        <i class="fa fa-star"></i>';
}elseif($user_level_2 == 'subadmin'){
	$rank = 'SubAdministrator';
	$rank2 ='<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>';
}elseif($user_level_2 == 'administrator'){
	$rank = '[Administrator]';
	$rank2 ='<i class="fa fa-star"></i>
	        <i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>';
}elseif($user_level_2 == 'superadmin'){
	$rank = '[Administrator]';
	$rank2 ='<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>';
}elseif($user_level_2 == 'developer'){
	$rank = '[Developer]';
	$rank2 ='<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>';
}else{
	$rank = 'Member Only';
	$rank2 ='<span class="glyphicon glyphicon-user"></span>';
}
$smarty->assign("rank", $rank);
$tableval = $datab1.$datab2.$tbdata;
$tablecontent = $db->decrypt_key2($tableval);
$tblcontent = $db->encryptor2('decrypt', $tablecontent);

if($is_groupname_2 == 'free'){
	$rank2 = 3;
}
$smarty->assign("rank2", $rank2);

if($profile_image == ''){
	$avatar = '<img class="rounded-circle mr-1" src="'.$default.'" alt="default">';
	$avatar2 = '<img class="mr-3 rounded-circle" width="50" src="'.$default.'" alt="default">';
	$avatar3 = '<img class="rounded-circle profile-widget-picture" width="250" src="'.$default.'" alt="default">';
	$avatar4 = '<img alt="default" src="'.$default.'" class="img-fluid">';
	$avatar6 = '<img class="mr-3 rounded-circle" width="50" src="'.$upline_default.'" alt="default">';
}else{
	$avatar = '<img class="rounded-circle mr-1" src="'.$profile.'" alt="'.$user_name_2.'">';
	$avatar2 = '<img class="mr-3 rounded-circle" width="50" src="'.$profile.'" alt="'.$user_name_2.'">';
	$avatar3 = '<img class="rounded-circle profile-widget-picture" width="250" src="'.$profile.'" alt="'.$user_name_2.'">';
	$avatar4 = '<img alt="'.$user_name_2.' src="'.$profile.'" class="img-fluid">';
	$avatar6 = '<img class="mr-3 rounded-circle" width="50" src="'.$upline_profile.'" alt="Upline">';
}

if($upline_image == ''){
	$avatar6 = '<img class="mr-3 rounded-circle" width="50" src="'.$upline_default.'" alt="default">';
}else{
	$avatar6 = '<img class="mr-3 rounded-circle" width="50" src="'.$upline_profile.'" alt="Upline">';
}

$smarty->assign("avatar", $avatar);
$smarty->assign("avatar2", $avatar2);
$smarty->assign("avatar3", $avatar3);
$smarty->assign("avatar4", $avatar4);
$smarty->assign("avatar6", $avatar6);

if(!is_logged_in($user)) {
	setcookie("user", NULL, time()-3600, "/"); 
	unset($_COOKIE['user']);
	$user = "";
	unset($user);
}

function chkSession() {
	global $user;
	if(!is_logged_in($user)) {
		header("Location: /login");
	}
}

function calc_time($seconds) {
	$days = (int)($seconds / 86400);
	$seconds -= ($days * 86400);
	if ($seconds) {
		$hours = (int)($seconds / 3600);
		$seconds -= ($hours * 3600);
	}
	if ($seconds) {
		$minutes = (int)($seconds / 60);
		$seconds -= ($minutes * 60);
	}
	$time = array('days'=>(int)$days,
			'hours'=>(int)$hours,
			'minutes'=>(int)$minutes,
			'seconds'=>(int)$seconds);
	return $time;
}

function ran_code() {
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	srand((double)microtime()*1000000);
	$i = 0;
	while ($i <= 4)
	{
		$num = rand() % 33;
		$tmp = substr($chars, $num, 1);
		$pwd = $pwd . $tmp;
		$i++;
	}
	return $pwd;
}

function ran_prefix() {
	$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
	srand((double)microtime()*1000000);
	$i = 0;
	while ($i <= 3)
	{
		$num = rand() % 33;
		$tmp = substr($chars, $num, 1);
		$pwd = $pwd . $tmp;
		$i++;
	}
	return $pwd;
}

function convertToReadableSize($size){
  $base = log($size) / log(1024);
  $suffix = array("B", "KB", "MB", "GB", "TB");
  $f_base = floor($base);
  return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
}

function generateRandomString($length = 20) {
    $characters = '0123456789abcdef';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function restructure_array(array $images)
{
	$result = array();

	foreach ($images as $key => $value) {
		foreach ($value as $k => $val) {
			for ($i = 0; $i < count($val); $i++) {
				$result[$i][$k] = $val[$i];
			}
		}
	}

	return $result;
}

function resizeImage($filename, $max_width, $max_height, $newfilename="", $withSampling=true)   
{   
   $width = 0;  
   $height = 0;  
  
   $newwidth = 0;  
   $newheight = 0;  
  
	// If no new filename was specified then use the original filename  
	if($newfilename == "")   
	{  
		$newfilename = $filename;   
	}  
      
	// Get original sizes   
	list($width, $height) = getimagesize($filename);   
      
	if($width > $height)  
	{  
		// We're dealing with max_width  
		if($width > $max_width)  
		{  
			$newwidth = $width * ($max_width / $width);  
			$newheight = $height * ($max_width / $width);  
		}else{  
			// No need to resize  
			$newwidth = $width;  
			$newheight = $height;  
		}  
	}else{  
		// Deal with max_height  
		if($height > $max_height)  
		{  
			$newwidth = $width * ($max_height / $height);  
			$newheight = $height * ($max_height / $height);  
		}else{  
			// No need to resize  
			$newwidth = $width;  
			$newheight = $height;  
		}  
	}  
  
	// Create a new image object   
	$thumb = imagecreatetruecolor($newwidth, $newheight);   
	imagealphablending($thumb, false);
	imagesavealpha($thumb, true);
	
	// Load the original based on it's extension  
	$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));  
  
	if($ext=='jpg' || $ext=='jpeg'){  
		$source = imagecreatefromjpeg($filename);   
	}elseif($ext=='gif'){  
		$source = imagecreatefromgif($filename);
		imagealphablending($source, true);		
	}elseif($ext=='png'){   
		$source = imagecreatefrompng($filename); 
		imagealphablending($source, true);		
	}else{  
		// Fail because we only do JPG, JPEG, GIF and PNG  
		return FALSE;  
	}  
      
	// Resize the image with sampling if specified  
	if($withSampling)   
	{  
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);   
	}else{     
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);   
	}  
          
	$imageQuality = 100;       
	// Save the new image   
	if($ext=='jpg' || $ext=='jpeg'){  
		return imagejpeg($thumb, $newfilename);   
	}elseif($ext=='gif'){  
      return imagegif($thumb, $newfilename);   
	}elseif($ext=='png'){  
		$scaleQuality = round(($imageQuality/100) * 9);
		$invertScaleQuality = 9 - $scaleQuality;
		return imagepng($thumb, $newfilename);   
	}
	imagedestroy($thumb);
}	

function get_time_elapsed($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v)
    {
        if ($diff->$k)
        {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        }
        else
        {
            unset($string[$k]);
	    }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function resizetable($tbl)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "$tbl");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            
    $data = curl_exec($ch);

    curl_close ($ch);
    
    if($data == 'true'){
         
    }else{
        echo $data; 
    }
    
    return true;
}

function getHTTPResponseStatusCode($url)
{
    $status = null;

    $headers = @get_headers($url, 1);
    if (is_array($headers)) {
        $status = substr($headers[0], 9);
    }

    return $status;
}

function pushFile($username,$token,$repo,$branch,$path,$b64data){
    $message = "Automated update";
    $ch1 = curl_init("https://api.github.com/repos/$repo/branches/$branch");
    curl_setopt($ch1, CURLOPT_HTTPHEADER, array('User-Agent:Php/Automated'));
    curl_setopt($ch1, CURLOPT_USERPWD, $username . ":" . $token);
    curl_setopt($ch1, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE);
    $data1 = curl_exec($ch1);
    curl_close($ch1);
    $data1=json_decode($data1,1);

    $ch2 = curl_init($data1['commit']['commit']['tree']['url']);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, array('User-Agent:Php/Firenet Developer'));
    curl_setopt($ch2, CURLOPT_USERPWD, $username . ":" . $token);
    curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
    $data2 = curl_exec($ch2);
    curl_close($ch2);
    $data2=json_decode($data2,1);

    $sha='';
    foreach($data2["tree"] as $file)
      if($file["path"]==$path)
        $sha=$file["sha"];
    
    $inputdata =[];
    $inputdata["path"]=$path;
    $inputdata["branch"]=$branch;
    $inputdata["message"]=$message;
    $inputdata["content"]=$b64data;
    $inputdata["sha"]=$sha;

    //echo json_encode($inputdata);

    $updateUrl="https://api.github.com/repos/$repo/contents/$path";
    //echo $updateUrl;
    $ch3 = curl_init($updateUrl);
    curl_setopt($ch3, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', 'User-Agent:Php/Ayan Dhara'));
    curl_setopt($ch3, CURLOPT_USERPWD, $username . ":" . $token);
    curl_setopt($ch3, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch3, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch3, CURLOPT_POSTFIELDS, json_encode($inputdata));
    $data3 = curl_exec($ch3);
    curl_close($ch3);
    
    return true;
}

$git_query = $db->sql_query("SELECT github_username, github_token FROM site_options WHERE id='1'");
$git_row = $db->sql_fetchrow($git_query);
$git_username = $git_row['github_username'];
$git_token = $git_row['github_token'];

$site_url = str_replace("www.", "", $_SERVER['SERVER_NAME']);

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');   

    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

//chat_status='seen' AND chat_id1='$user_id_2' OR
$chat_support =  $db->sql_query("SELECT * FROM chat WHERE chat_status='seen' AND chat_id2 = '$user_id_2'");
if($db->sql_numrows($chat_support) > 0){
	$alert_chat = '<span class="badge badge-info up">'.$db->sql_numrows($chat_support).'</span>';
}else{
	$alert_chat = '';
}
$smarty->assign("alert_chat", $alert_chat);

if($user_id_2 == 1 || $user_id_2 == 5){
	$staff_support =  $db->sql_query("SELECT * FROM support_ticket WHERE ticket_status = 'customer-reply' OR ticket_status = 'open'");
}else{
	$staff_support =  $db->sql_query("SELECT * FROM support_ticket WHERE ticket_user_id='$user_id_2' AND ticket_status = 'answered'");
}

if($db->sql_numrows($staff_support) > 0){
	$alert_message = '<span class="label label-round label-info">'.$db->sql_numrows($staff_support).'</span>';
}else{
	$alert_message = '';
}
$smarty->assign("alert_message", $alert_message);

$siteURL = str_replace('https://','', $_SERVER['SERVER_NAME']);
$smarty->assign("siteURL", $siteURL);

$deviceOS = $db->getOS()." - ".$db->getBrowserM();
$androidModel = $db->getDeviceModel();
$useragent = $_SERVER['HTTP_USER_AGENT'];
$str = $useragent;
                            
if($detect->isAndroidOS()){
    $pos1 = strpos($str, '(')+1;
    $pos2 = strpos($str, ')')-$pos1;
    $part = substr($str, $pos1, $pos2);
    $parts = explode(" ", $part);
    $device_client = 'Smartphone ('.$androidModel.')';
}elseif(!$detect->isMobile() && !$detect->isTablet()){
    $device_client = 'Desktop';
}
?>



