<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
require_once '../includes/functions.php';

$hash = $_POST['hash'];
$data = $_POST['data'];

if(isset($data) && isset($hash)){
 
 	$file = fopen("../uploads/json/$hash.json","w");
                ob_end_clean();
                $fwrite = fwrite($file,$data);
                $fclose = fclose($file);
                
                if($fwrite && $fclose){
                    echo 'Success';
                }else{
                    echo 'Failed';
                }

}else{
 	echo 'Request denied';
}
?>