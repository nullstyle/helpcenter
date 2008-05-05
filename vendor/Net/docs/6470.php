<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once '../URL.php';

$url = new Net_URL;
$url->setOption('encode_query_keys', true);
print_r($url->querystring);
?>
