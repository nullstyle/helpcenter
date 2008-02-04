<?

# Directory where the Sprinkles code, and the caches, reside.
$sprinkles_dir = dirname(__FILE__);

# Use cached feeds? # FIXME: get rid of this nonsense.
global $quick_mode;
# $quick_mode = true;
$quick_mode = false;
global $cache_dir;
$cache_dir = $sprinkles_dir . "caches/";

# API root URL and caching directory
$api_root = 'http://api.getsatisfaction.com/';

$mysql_username = '';
$mysql_password = '';

# Configure this if you need to talk to a MySQL database on another machine
# or use a nonstandard port. The default should normally work. 
# (FIXME: this doesn't apparently do anything.)
$mysql_connect_params = 'localhost:3306';

# Smarty configuration
require_once('Smarty/Smarty.class.php');
$smarty = new Smarty();
$smarty->template_dir = $sprinkles_dir . '/templates/';
$smarty->compile_dir  = $sprinkles_dir . '/templates_c/';
$smarty->config_dir   = $sprinkles_dir . '/configs/';
$smarty->cache_dir    = $sprinkles_dir . '/cache/';

# page limits

$helpstart_topic_count = 5; 
$discuss_topic_page_limit = 3;
$related_topics_count = 4;
?>
