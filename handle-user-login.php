<?

require_once('Sprinkles.php');
require_once('HTTP_Request_Oauth.php');

$oauth_req = new HTTP_Request_OAuth(
                   'http://getsatisfaction.com/api/request_token',
                   array('consumer_key' => 'lmwjv4kzwi27',
                         'consumer_secret' => 'fiei6iv61jnoukaq1aylwd8vcmnkafrs',
                         'signature_method' => 'HMAC-SHA1',
                         'method' => 'GET'));

$resp = $oauth_req->sendRequest(true, true);
#dump($oauth_req->getResponseBody());
list($token, $secret) = $oauth_req->getResponseTokenSecret();
error_log("request token: $token, $secret");

if (!$token || !$token_secret)
  die("Failed to fetch OAuth request token from getsatisfaction.com.");

$result = mysql_query('insert into oauth_tokens (token, token_secret) values (\''
                      . $token . '\', \'' . $secret . '\')');

if (!$result) die("Error inserting OAuth tokens into database.");

$sprinkles_root_url = 'http://localhost/sprinkles/';

$return = request_param('return');
$callback_url = $sprinkles_root_url . 'handle-oauth-return.php?return=' . 
                urlencode($return);

# FIXME: hardcoded API URL
$url = 'http://getsatisfaction.com/api/authorize?oauth_token='
        . $token . '&oauth_callback=' . $callback_url;

redirect($url);

?>