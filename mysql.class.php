<?PHP
if (preg_match("/mysql.class.php/i", $_SERVER['SCRIPT_NAME'])) {
    Header("Location: index.php"); die();
}
include 'phpmailer/PHPMailerAutoload.php';

class mysql_db
{
    var $success_message;
    var $successf_message;
    var $successf_icon;
    var $successf_title;
    var $error_message;
    var $warninglogin_message;
    var $errorlogin_message;
	
    var $username;
    var $pwd;
    var $database;
    var $connection;

	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	
	var $siteTitle;
	var $sitename;
	var $bakfrom;
	var $bakcc;

    function InitDB($host,$uname,$pwd,$database)
    {
        $this->db_host  = $host;
        $this->username = $uname;
        $this->pwd  = $pwd;
        $this->database  = $database;	
    }

	function sql_query($query)
	{
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }

		//unset($this->query_result);
		if($query != "")
		{
			$this->query_result = $this->connection->query($query);
		}
		
		if($this->query_result)
		{
			//unset($this->row[$this->query_result]);
			//unset($this->rowset[$this->query_result]);
			return $this->query_result;
		}
			return;
	}

	function sql_numrows($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = $query_id->num_rows;
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_affectedrows()
	{
		if($this->connection)
		{
			$result = $this->connection->affected_rows;
			return $result;
		}
		else
		{
			return false;
		}

	}

	function sql_fetchrow($query_id = null)
	{
		if(empty($query_id))
		{
			$query_id = $this->query_result;
		}
		
		if($query_id)
		{
			$result = $query_id->fetch_assoc();
			
			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_fetcharray($query_id = null)
	{
		if(empty($query_id))
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = $query_id->fetch_array();
			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_fetchobject($query_id = null)
	{
		if(empty($query_id))
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = $query_id->fetch_object();
			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_fetchrowset($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			unset($this->rowset[$query_id]);
			unset($this->row[$query_id]);
			while($this->rowset[$query_id] = $query_id->fetch_assoc())
			{
				$result[] = $this->rowset[$query_id];
			}
			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_fetcharrayset($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			unset($this->rowset[$query_id]);
			unset($this->row[$query_id]);
			while($this->rowset[$query_id] = $query_id->fetch_array())
			{
				$result[] = $this->rowset[$query_id];
			}
			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_numfields($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = $query_id->num_fields;
			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_fieldname($offset, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = $query_id->field($offset);
			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_fieldtype($offset, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = $query_id->field($offset);
			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_rowseek($rownum, $query_id = 0){
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = $query_id->data_seek($rownum);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_nextid(){
		if($this->connection)
		{
			$result = $this->connection->insert_id;
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_freeresult($query_id = 0){
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}

		if ( $query_id )
		{
			unset($this->row[$query_id]);
			unset($this->rowset[$query_id]);

			$query_id->free_result();

			return true;
		}
		else
		{
			return false;
		}
	}
	function sql_error($query_id = 0)
	{
		$result["message"] = $this->connection->error;
		$result["code"] = $this->connection->errno;

		return $result;
	}
	
    function DBLogin()
    {

        $this->connection = new MySQLi($this->db_host,$this->username,$this->pwd);

        if(!$this->connection)
        {   
            $this->HandleDBError("Database Login failed! Please make sure that the DB login credentials provided are correct");
            return false;
        }
        if(!$this->connection->select_db($this->database))
        {
            $this->HandleDBError('Failed to select database: '.$this->database.' Please make sure that the database name provided is correct');
            return false;
        }
        if(!$this->connection->query("SET NAMES 'UTF8'"))
        {
            $this->HandleDBError('Error setting utf8 encoding');
            return false;
        }
        return true;
    }

    function SetWebsiteTitle($siteTitle)
    {
        $this->siteTitle = $siteTitle;
    }

    function SetWebsiteName($sitename)
    {
        $this->sitename = $sitename;
    }
    
    function SetBakTo($bakto)
    {
        $this->bakto = $bakto;
    }
    
    function SetBakCC($bakcc)
    {
        $this->bakcc = $bakcc;
    }

    function GetAbsoluteURLFolder()
    {
        $scriptFolder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
        $scriptFolder .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
        return $scriptFolder;
    }

	function base_url()
	{
        $Folder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' . $_SERVER['HTTP_HOST'] .'/' : 'http://' . $_SERVER['HTTP_HOST'] .'/';

        return $Folder;
	}

    function GetSelfScript()
    {
        return htmlentities($_SERVER['PHP_SELF']);
    }

	function encrypt_key($paswd)
	{
	  $mykey=$this->getEncryptKey();
	  $encryptedPassword=$this->encryptPaswd($paswd,$mykey);
	  return $encryptedPassword;
	}
	 
	// function to get the decrypted user password
	function decrypt_key($paswd)
	{
	  $mykey=$this->getEncryptKey();
	  $decryptedPassword=$this->decryptPaswd($paswd,$mykey);
	  return $decryptedPassword;
	}
	 
	function getEncryptKey()
	{
		$secret_key = md5('firenet');
		$secret_iv = md5('philippines');
		$keys = $secret_key . $secret_iv;
		return $this->encryptor('encrypt', $keys);
	}
	function encryptPaswd($string, $key)
	{
	  $result = '';
	  for($i=0; $i<strlen ($string); $i++)
	  {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	  }
		return base64_encode($result);
	}
	 
	function decryptPaswd($string, $key)
	{
	  $result = '';
	  $string = base64_decode($string);
	  for($i=0; $i<strlen($string); $i++)
	  {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
	  }
	 
		return $result;
	}
	
	function encryptor($action, $string) {
		$output = false;

		$encrypt_method = "AES-256-CBC";
		//pls set your unique hashing key
		$secret_key = md5('firenet philippines');
		$secret_iv = md5('philippines firenet');

		// hash
		$key = hash('sha256', $secret_key);
		
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);

		//do the encyption given text/string/number
		if( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		}
		else if( $action == 'decrypt' ){
			//decrypt the given text/string/number
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}

		return $output;
	}
	
	//Core4VPN Developer
	function encrypt_key2($paswd)
	{
	  $mykey=$this->getEncryptKey2();
	  $encryptedPassword=$this->encryptPaswd2($paswd,$mykey);
	  return $encryptedPassword;
	}
	 
	// function to get the decrypted user password
	function decrypt_key2($paswd)
	{
	  $mykey=$this->getEncryptKey2();
	  $decryptedPassword=$this->decryptPaswd2($paswd,$mykey);
	  return $decryptedPassword;
	}
	 
	function getEncryptKey2()
	{
		$secret_key = md5('lenz');
		$secret_iv = md5('kennedy');
		$keys = $secret_key . $secret_iv;
		return $this->encryptor2('encrypt', $keys);
	}
	function encryptPaswd2($string, $key)
	{
	  $result = '';
	  for($i=0; $i<strlen ($string); $i++)
	  {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	  }
		return base64_encode($result);
	}
	 
	function decryptPaswd2($string, $key)
	{
	  $result = '';
	  $string = base64_decode($string);
	  for($i=0; $i<strlen($string); $i++)
	  {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
	  }
	 
		return $result;
	}
	
	function encryptor2($action, $string) {
		$output = false;

		$encrypt_method = "AES-256-CBC";
		//pls set your unique hashing key
		$secret_key = md5('lenz kennedy');
		$secret_iv = md5('kennedy lenz');

		// hash
		$key = hash('sha256', $secret_key);
		
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);

		//do the encyption given text/string/number
		if( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		}
		else if( $action == 'decrypt' ){
			//decrypt the given text/string/number
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}

		return $output;
	}
	
	function time_elapsed_string($ptime)
	{
		$etime = time() - $ptime;

		if ($etime < 1)
		{
			return '0 seconds';
		}

		$a = array( 365 * 24 * 60 * 60  =>  'year',
					 30 * 24 * 60 * 60  =>  'month',
						  24 * 60 * 60  =>  'day',
							   60 * 60  =>  'hour',
									60  =>  'minute',
									 1  =>  'second'
					);
		$a_plural = array( 'year'   => 'years',
						   'month'  => 'months',
						   'day'    => 'days',
						   'hour'   => 'hours',
						   'minute' => 'minutes',
						   'second' => 'seconds'
					);

		foreach ($a as $secs => $str)
		{
			$d = $etime / $secs;
			if ($d >= 1)
			{
				$r = round($d);
				return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
			}
		}
	}

	function gen_id() 
	{ 
		$id = 'a'; 

		for ($i=1; $i<=12; $i++) { 
			if (rand(0,1)) { 
				// letter 
				$id .= chr(rand(65, 90)); 
			} else {
				// number;
				$id .= rand(0, 9); 
			}
		}
		return $id;
	}

	function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	function getBrowser()
	{
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";

		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}

		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}
		elseif(preg_match('/Firefox/i',$u_agent))
		{
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		elseif(preg_match('/Chrome/i',$u_agent))
		{
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}
		elseif(preg_match('/Safari/i',$u_agent))
		{
			$bname = 'Apple Safari';
			$ub = "Safari";
		}
		elseif(preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
		}
		elseif(preg_match('/Netscape/i',$u_agent))
		{
			$bname = 'Netscape';
			$ub = "Netscape";
		}

		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}

		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}

		// check if we have a number
		if ($version==null || $version=="") {$version="?";}

		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	}

    function RedirectToURL($url)
    {
        header("Location: $url");
        exit;
    }

    function GetErrorMessage()
    {
        if(empty($this->error_message))
        {
            return '';
        }
        $errormsg = '';
		$errormsg .= "<div class='alert alert-danger'>";
		$errormsg .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;";
		$errormsg .= "</button>";
		$errormsg .= nl2br(htmlentities($this->error_message));
		$errormsg .= "</div>";
        return $errormsg;
    }
    
    function GetWarningLogin()
    {
        if(empty($this->warninglogin_message))
        {
            return '';
        }
        $warninglogin = '';
		$warninglogin .= "<div class='alert alert-warning alert-has-icon'>";
		$warninglogin .= "<div class='alert-icon'>";
        $warninglogin .= "<i class='far fa-lightbulb'></i>";
        $warninglogin .= "</div>";
        $warninglogin .= "<div class='alert-body'>";
		$warninglogin .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;";
		$warninglogin .= "</button>";
		$warninglogin .= "<div class='alert-title'>Oops</div>";
		$warninglogin .= nl2br(htmlentities($this->warninglogin_message));
		$warninglogin .= "</div>";
		$warninglogin .= "</div>";
        return $warninglogin;
    }
    
    function GetErrorLogin()
    {
        if(empty($this->errorlogin_message))
        {
            return '';
        }
        $errorlogin = '';
		$errorlogin .= "<div class='alert alert-danger alert-has-icon'>";
		$errorlogin .= "<div class='alert-icon'>";
        $errorlogin .= "<i class='far fa-lightbulb'></i>";
        $errorlogin .= "</div>";
        $errorlogin .= "<div class='alert-body'>";
		$errorlogin .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;";
		$errorlogin .= "</button>";
		$errorlogin .= "<div class='alert-title'>Oops</div>";
		$errorlogin .= nl2br(htmlentities($this->errorlogin_message));
		$errorlogin .= "</div>";
		$errorlogin .= "</div>";
        return $errorlogin;
    }

    function GetSuccessMessage()
    {
        if(empty($this->success_message))
        {
            return '';
        }
        $successmsg = '';
		$successmsg .= "<div class='alert alert-success'>";
		$successmsg .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;";
		$successmsg .= "</button>";
		$successmsg .= nl2br(htmlentities($this->success_message));
		$successmsg .= "</div>";
        return $successmsg;
    }
    
    function GetSuccessFMessage()
    {
        if(empty($this->successf_message))
        {
            return '';
        }
        if(empty($this->successf_icon))
        {
            return '';
        }
        if(empty($this->successf_title))
        {
            return '';
        }
        $successf = '';
		$successf .= "<div class='alert alert-success alert-has-icon'>";
		$successf .= "<div class='alert-icon'>";
        $successf .= "<i class='".$this->successf_icon."'></i>";
        $successf .= "</div>";
        $successf .= "<div class='alert-body'>";
		$successf .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;";
		$successf .= "</button>";
		$successf .= "<div class='alert-title'>".$this->successf_title."</div>";
		$successf .= nl2br(htmlentities($this->successf_message));
		$successf .= "</div>";
		$successf .= "</div>";
        return $successf;
    }

    function HandleSuccess($suc)
    {
		$this->success_message = $suc."\r\n";
    }
    
    function HandleSuccessF($sucf_icon,$sucf_title,$sucf_message)
    {
        $this->successf_icon = $sucf_icon;
        $this->successf_title = $sucf_title;
		$this->successf_message = $sucf_message."\r\n";
    }

    function HandleError($err)
    {
		$this->error_message = $err."\r\n";
    }
    
    function HandleErrorLogin($errlog)
    {
		$this->errorlogin_message = $errlog."\r\n";
    }
    
    function HandleWarningLogin($warlog)
    {
		$this->warninglogin_message = $warlog."\r\n";
    }

    function HandleDBError($err)
    {
        $this->HandleError($err."\r\n ". mysqli_error($this->db_connect_id). ":");
    }

    function SanitizeForSQL($str)
    {
		if(!$this->DBLogin())
		{
			$this->HandleError("Database login failed!");
			return false;
		}
		
        if( function_exists("mysqli_real_escape_string") )
        {
			$ret_str = $this->connection->real_escape_string($str);
        }
        else
        {
              $ret_str = addslashes($str);
        }
        return $ret_str;
    }

    function Sanitize($str,$remove_nl=true)
    {
        $str = $this->StripSlashes($str);

        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
            $str = preg_replace($injections,'',$str);
        }

        return $str;
    }    
    function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }

	function calc_time($seconds) {
		$days = (int)($seconds / 86400);
		$seconds -= ($days * 86400);
		$minutes = '';
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
	
	function time_to_iso8601_duration($time) {
		$units = array(
			"Y" => 365*24*3600,
			"D" =>     24*3600,
			"H" =>        3600,
			"M" =>          60,
			"S" =>           1,
		);

		$str = "P";
		$istime = false;

		foreach ($units as $unitName => &$unit) {
			$quot  = intval($time / $unit);
			$time -= $quot * $unit;
			$unit  = $quot;
			if ($unit > 0) {
				if (!$istime && in_array($unitName, array("H", "M", "S"))) { // There may be a better way to do this
					$str .= "T";
					$istime = true;
				}
				$str .= strval($unit) . $unitName;
			}
		}

		return $str;
	}

	function openvpnLogs($log) {
		$handle = fopen($log, "r");
		$uid = 0;
		while (!feof($handle)) {
			$buffer = fgets($handle, 4096);
			unset($match);
			if (preg_match("^Updated,(.+)", $buffer, $match)) { 
				$status['updated'] = $match[1];
			}
			if (preg_match("/^(.+),(\d+\.\d+\.\d+\.\d+\:\d+),(\d+),(\d+),(.+)$/", $buffer, $match)) {
				if ($match[1] <> "Common Name") {
					$cn = $match[1];

					$userlookup[$match[2]] = $uid;

					$status['users'][$uid]['CommonName'] = $match[1];
					$status['users'][$uid]['RealAddress'] = $match[2];
					$status['users'][$uid]['BytesReceived'] = $match[3];
					$status['users'][$uid]['BytesSent'] = $match[4];
					$status['users'][$uid]['Since'] = $match[5];

					$uid++;
				}
			}

			if (preg_match("/^(\d+\.\d+\.\d+\.\d+),(.+),(\d+\.\d+\.\d+\.\d+\:\d+),(.+)$/", $buffer, $match)) {
				if ($match[1] <> "Virtual Address") {
					$address = $match[3];

					$uid = $userlookup[$address];

					$status['users'][$uid]['VirtualAddress'] = $match[1];
					$status['users'][$uid]['LastRef'] = $match[4];
				}
			}

		}

		fclose($handle);

		return($status);
	}

	function sizeformat($bytesize){
		$i=0;
		while(abs($bytesize) >= 1024){
			$bytesize=$bytesize/1024;
			$i++;
			if($i==4) break;
		}

		$units = array("Bytes","KB","MB","GB","TB");
		$newsize=round($bytesize,2);
		return("$newsize $units[$i]");
	}
	
	function get_data($url) {
		$ch = curl_init();
		$timeout = 5;
		$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	function ran_code() {
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$pwd = '';
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
	
	function Contact()
	{
        $formvars = array();
		
        if(!$this->ValidateContactSubmission())
        {
            return false;
        }

        $this->CollectContactSubmission($formvars);

        $this->SendingEmail($formvars);
        return true;
	}
	
	function ValidateContactSubmission()
	{
		if(empty($_POST['contact_name'])){
			$this->HandleError("Contact Name is empty!");
			return false;
		}

		if(empty($_POST['contact_phone'])){
			$this->HandleError("Contact Phone is empty!");
			return false;
		}

		if(empty($_POST['contact_email']) || !filter_var($_POST['contact_email'],FILTER_VALIDATE_EMAIL)){
			$this->HandleError("Sorry! the email address is Invalid!". "\r\n" ."Please enter a valid email address!");
			return false;
		}

		if(empty($_POST['contact_subject'])){
			$this->HandleError("Contact Subject is empty!");
			return false;
		}

		if(empty($_POST['contact_message'])){
			$this->HandleError("Contact Message is empty!");
			return false;
		}

		return true;
	}

    function CollectContactSubmission(&$formvars)
    {
        $formvars['name'] = $this->Sanitize($_POST['contact_name']);
        $formvars['phone'] = $this->Sanitize($_POST['contact_phone']);
        $formvars['email'] = $this->Sanitize($_POST['contact_email']);
		$formvars['subject'] = $this->Sanitize($_POST['contact_subject']);
		$formvars['message'] = $this->Sanitize($_POST['contact_message']);
		$formvars['attachment'] = $_FILES['attachment']['name'];
		$formvars['tmp_name'] = $_FILES['attachment']['tmp_name'];
		$formvars['size'] = $_FILES['attachment']['size'];
    }

   function SendingEmail(&$formvars)
    {
        $mailer = new PHPMailer();
        $mailer->CharSet = 'utf-8'; 

		$mailer->From		= $formvars['email'];
		$mailer->FromName	= $formvars['name'];
		$mailer->AddAddress("admin@gmail.com");

		$mailer->addReplyTo($formvars['email']);
		$mailer->addCC($formvars['email']);

		if(isset($formvars['attachment']) && $formvars['attachment'] != '') { // name|type|tmp_name|error|size|
			$target_dir		= "../_uploads/";
			$target_file 	= basename($formvars['attachment']);
			$rename 		= time() . '_' . $target_file;
			$uploadOk 		= 1;
			$imageFileType  = pathinfo($target_dir .$target_file,PATHINFO_EXTENSION);
			
			if(is_dir( $target_dir ) == false)
			{
				mkdir( $target_dir, 0777, true) or die('Error: ' . $this->connection->error);
			}
			// Check if image file is a actual image or fake image
			if(isset($_POST["submitted"])) {
				$check = getimagesize($formvars['tmp_name']);
				if($check !== false) {
					$uploadOk = 1;
				} else {
					$this->HandleError("File is not an image.");
					$uploadOk = 0;
				}
			}
			// Check if file already exists
			if (file_exists($target_file)) {
				$this->HandleError("Sorry, file already exists.");
				$uploadOk = 0;
			}
			// Check file size
			if ($formvars['size'] > 10000000) {
				$this->HandleError("Sorry, your file is too large.");
				$uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" && $imageFileType != "zip" && $imageFileType != "rar" 
			&& $imageFileType != "doc" && $imageFileType != "docx" && $imageFileType != "pdf") {
				$this->HandleError("Sorry, only JPG, JPEG, PNG & GIF & ZIP & RAR & PDF files are allowed.");
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				$this->HandleError("Sorry, your file was not uploaded.");
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($formvars['tmp_name'], $target_dir . $rename)) {
					$mailer->AddAttachment($path = $target_dir . $rename, $name = $rename, $encoding = 'base64', $type = 'application/octet-stream');
				} else {
					$this->HandleError("Sorry, there was an error uploading your file.");
				}
			}
		}

        $mailer->Subject	= $formvars['subject']." - Contact Form:  ".$formvars['name'];
        $mailer->Body		= "You have received a new message from your website's contact form.\r\n" .
							  "Here are the details:\r\n" .
							  "Name: " . $formvars['name'] . "\r\n" . "\r\n" .
							  "Contact Number: " . $formvars['phone'] . "\r\n" . "\r\n" .
							  "Message: " . $formvars['message'] . "\r\n" . "\r\n" . "\r\n" . 
							  "Supported by: " . $this->siteTitle . "\r\n" .
							  "Web Developer: " . "jhoexii" . "\r\n" .
							  "Please Visit Our website: " . $this->base_url() . "\r\n" . "\r\n" .
							  "IP Address: " .  $this->get_client_ip() . "\r\n" .
							  "Browser: " . $_SERVER['HTTP_USER_AGENT'] . "\r\n";

		if(!$mailer->Send())
        {
            echo 'Mailer Error: ' . $mailer->ErrorInfo;
			$this->HandleError("Failed sending registration confirmation email.");
			return false;
        }
		return true;
    }
    
    function __backup_mysql_database($params)
	{
		$mtables = array(); $contents = "-- Database: `".$params['db_to_backup']."` --\n";
	   
		$mysqli = new mysqli($params['db_host'], $params['db_uname'], $params['db_password'], $params['db_to_backup']);
		if ($mysqli->connect_error) {
			die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
		}
	   
		$results = $mysqli->query("SHOW TABLES");
	   
		while($row = $results->fetch_array()){
			if (!in_array($row[0], $params['db_exclude_tables'])){
				$mtables[] = $row[0];
			}
		}

		foreach($mtables as $table){
			$contents .= "-- Table `".$table."` --\n";
		   
			$results = $mysqli->query("SHOW CREATE TABLE ".$table);
			while($row = $results->fetch_array()){
				$contents .= $row[1].";\n\n";
			}

			$results = $mysqli->query("SELECT * FROM ".$table);
			$row_count = $results->num_rows;
			$fields = $results->fetch_fields();
			$fields_count = count($fields);
		   
			$insert_head = "INSERT INTO `".$table."` (";
			for($i=0; $i < $fields_count; $i++){
				$insert_head  .= "`".$fields[$i]->name."`";
					if($i < $fields_count-1){
							$insert_head  .= ', ';
						}
			}
			$insert_head .=  ")";
			$insert_head .= " VALUES\n";       
				   
			if($row_count>0){
				$r = 0;
				while($row = $results->fetch_array()){
					if(($r % 400)  == 0){
						$contents .= $insert_head;
					}
					$contents .= "(";
					for($i=0; $i < $fields_count; $i++){
						$row_content =  str_replace("\n","\\n",$mysqli->real_escape_string($row[$i]));
					   
						switch($fields[$i]->type){
							case 8: case 3:
								$contents .=  $row_content;
								break;
							default:
								$contents .= "'". $row_content ."'";
						}
						if($i < $fields_count-1){
								$contents  .= ', ';
							}
					}
					if(($r+1) == $row_count || ($r % 400) == 399){
						$contents .= ");\n\n";
					}else{
						$contents .= "),\n";
					}
					$r++;
				}
			}
		}
		
		if(is_dir( $params['db_backup_path'] ) == false)
		{
			mkdir( $params['db_backup_path'], 0777, true) or die('Error: ' . $mysqli->error);
		}	
	   
		$backupfile = $params['db_backup_path'] . "sql-backup-".date( "d-m-Y--h-i-s").".sql";
		$file_name = "sql-backup-".date( "d-m-Y--h-i-s").".sql";
		$fp = gzopen($backupfile.'.gz','wb9');
		if (($result = gzwrite($fp,$contents))) {
			$file = $backupfile . ".gz";
			//echo "<p><h4 class='text-center'>Backup file created '--$file_name' ($result)</h4></p>";
		}
		gzclose($fp);


        $mailer = new PHPMailer();   
		
        $mailer->CharSet = 'utf-8'; 
		$From = "admin@".$_SERVER['HTTP_HOST'];
		$FromName = $this->sitename." - ".$this->siteTitle;
		$Subject = $this->sitename . " - Database Backup";
		$mailer->AddAddress($this->bakto);
		$mailer->From		= $From;
		$mailer->FromName	= $FromName;
		$mailer->addCC($this->bakcc);
		
        $mailer->Subject	= $Subject;


		$mailer->AddAttachment($file, $name = $file, $encoding = 'base64', $type = 'application/octet-stream');
        $mailer->Body		= "See attached database backup file. \r\n \r\n".
							  "Regards,\r\n".
							  $this->sitename."\r\n".$From;
		
		if(!$mailer->Send())
        {
            echo 'Mailer Error: ' . $mailer->ErrorInfo;		
			return false;
        }
	
		// Delete the file from your server
		$unlink = $backupfile.'.gz';
		unlink($unlink);

		//echo "Generated Backup is successfully <br />". $backupfile;
	}
    
    function getOS() { 

    	$user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    	$os_platform =   "Bilinmeyen İşletim Sistemi";
    	$os_array =   array(
    		'/windows nt 10/i'      =>  'Windows 10',
    		'/windows nt 6.3/i'     =>  'Windows 8.1',
    		'/windows nt 6.2/i'     =>  'Windows 8',
    		'/windows nt 6.1/i'     =>  'Windows 7',
    		'/windows nt 6.0/i'     =>  'Windows Vista',
    		'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
    		'/windows nt 5.1/i'     =>  'Windows XP',
    		'/windows xp/i'         =>  'Windows XP',
    		'/windows nt 5.0/i'     =>  'Windows 2000',
    		'/windows me/i'         =>  'Windows ME',
    		'/win98/i'              =>  'Windows 98',
    		'/win95/i'              =>  'Windows 95',
    		'/win16/i'              =>  'Windows 3.11',
    		'/macintosh|mac os x/i' =>  'Mac OS X',
    		'/mac_powerpc/i'        =>  'Mac OS 9',
    		'/linux/i'              =>  'Linux',
    		'/ubuntu/i'             =>  'Ubuntu',
    		'/iphone/i'             =>  'iPhone',
    		'/ipod/i'               =>  'iPod',
    		'/ipad/i'               =>  'iPad',
    		'/android/i'            =>  'Android',
    		'/blackberry/i'         =>  'BlackBerry',
    		'/webos/i'              =>  'Mobile'
    	);
    
    	foreach ( $os_array as $regex => $value ) { 
    		if ( preg_match($regex, $user_agent ) ) {
    			$os_platform = $value;
    		}
    	}   
    	return $os_platform;
    }
    
    function getBrowserM() {
    	$user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    	$browser        = "Bilinmeyen Tarayıcı";
    	$browser_array  = array(
    		'/msie/i'       =>  'Internet Explorer',
    		'/firefox/i'    =>  'Firefox',
    		'/safari/i'     =>  'Safari',
    		'/chrome/i'     =>  'Chrome',
    		'/edge/i'       =>  'Edge',
    		'/opera/i'      =>  'Opera',
    		'/opr/i'      =>  'Opera',
    		'/orca-android/i'      =>  'Facebook Messenger',
    		'/fb4a/i'      =>  'Facebook Browser',
    		'/netscape/i'   =>  'Netscape',
    		'/maxthon/i'    =>  'Maxthon',
    		'/konqueror/i'  =>  'Konqueror'
    	);
    
    	foreach ( $browser_array as $regex => $value ) { 
    		if ( preg_match( $regex, $user_agent ) ) {
    			$browser = $value;
    		}
    	}
    	return $browser;
    }
    
    function getDeviceModel() {
    	$user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    	$device        = "Android";
    	$device_array  = array(
    		'/ABR-AL60/i'       =>  'Huawei P50E',
    		'/JLN-LX1/i'       =>  'Huawei nova 9 SE',
    		'/JLN-LX3/i'       =>  'Huawei nova 9 SE',
    		'/JuliaQN-L01B/i'       =>  'Huawei nova 9 SE',
    		'/JuliaQN-L21B/i'       =>  'Huawei nova 9 SE',
    		'/JuliaQN-L23A/i'       =>  'Huawei nova 9 SE',
    		'/BAL-AL00/i'       =>  'Huawei P50 Pocket',
    		'/BAL-L49/i'       =>  'Huawei P50 Pocket',
    		'/JSC-AL50/i'       =>  'Huawei nova 8 SE 4G',
    		
            
    		'/DUB-LX2/i'       =>  'Huawei Y7 Pro 2019'
    	);
    
    	foreach ( $device_array as $regex => $value ) { 
    		if ( preg_match( $regex, $user_agent ) ) {
    			$device = $value;
    		}
    	}
    	return $device;
    }
}
?>
	