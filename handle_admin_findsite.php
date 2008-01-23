<?

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$site = request_param('site');
$oauth_consumer_key = request_param('oauth_consumer_key');
$oauth_consumer_secret = request_param('oauth_consumer_secret');
# TBD: validate these fields.
$result = $sprink->set_site_settings(
                array('company_id' => $site,
                'oauth_consumer_key' => $oauth_consumer_key,
                'oauth_consumer_secret' => $oauth_consumer_secret));
if (!$result) die (mysql_error());

redirect('handle-user-login.php?return=admin.php?hooked=true');

?>
