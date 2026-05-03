<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
define('DOC_ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/');
require DOC_ROOT_PATH . './includes/functions.php';
$base_url = $db->base_url();
header('Location: '. $base_url);
?>