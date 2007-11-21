<?
require('Smarty.class.php');

$sprinkles_dir = '/Library/WebServer/Documents/sprinkles/';

$quick_mode = true;
# $quick_mode = false;

$cache_dir = $sprinkles_dir . "caches/";
$api_root = 'http://api.getsatisfaction.com';

$smarty = new Smarty();
$smarty->template_dir = $sprinkles_dir . 'templates/';
$smarty->compile_dir  = $sprinkles_dir . 'templates_c/';
$smarty->config_dir   = $sprinkles_dir . 'configs/';
$smarty->cache_dir    = $sprinkles_dir . 'cache/';

$company_id = 5711;

$helpstart_topic_count = 5; 
$discuss_topic_count = 3;
?>
