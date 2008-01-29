<?

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$site = request_param('site');
# TBD: validate these fields.
$oauth_consumer_key = request_param('oauth_consumer_key');
$oauth_consumer_secret = request_param('oauth_consumer_secret');
$sprinkles_root_url = request_param('sprinkles_root_url');
$sprinkles_root_url = preg_replace('|[^/]*.php$|', '', $sprinkles_root_url);
$sprinkles_root_url = preg_replace('|/*$|', '/', $sprinkles_root_url); # FIXME doesn't do the trick.

$result = $sprink->set_site_settings(
                array('company_id' => $site,
                'oauth_consumer_key' => $oauth_consumer_key,
                'oauth_consumer_secret' => $oauth_consumer_secret,
                'sprinkles_root_url' => $sprinkles_root_url));
if (!$result) die (mysql_error());

redirect('handle-user-login.php?return=admin.php?hooked=true');

?>