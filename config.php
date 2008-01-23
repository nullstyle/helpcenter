<?

# Directory where the Sprinkles code, and the caches, reside.
$sprinkles_dir = '/Library/WebServer/Documents/sprinkles/';

# Use cached feeds?
global $quick_mode;
# $quick_mode = true;
$quick_mode = false;

# API root URL and caching directory

$api_root = 'http://api.getsatisfaction.com/';
global $cache_dir;
$cache_dir = $sprinkles_dir . "caches/";

# smarty configuration
require_once('Smarty.class.php');
$smarty = new Smarty();
$smarty->template_dir = $sprinkles_dir . 'templates/';
$smarty->compile_dir  = $sprinkles_dir . 'templates_c/';
$smarty->config_dir   = $sprinkles_dir . 'configs/';
$smarty->cache_dir    = $sprinkles_dir . 'cache/';

# page limits

$helpstart_topic_count = 5; 
$discuss_topic_page_limit = 3;
$related_topics_count = 4;
?>
