<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
require_once '../includes/functions.php';

if(isset($_GET['data']) && isset($_GET['hash'])){
    $hash = strip_tags(trim(strtolower($_GET['hash'])));
 	$data = strip_tags(trim($_GET['data']));
 
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