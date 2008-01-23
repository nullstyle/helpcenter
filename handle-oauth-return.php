<?

require_once('Sprinkles.php');
require_once('HTTP_Request_Oauth.php');

$request_token = request_param('oauth_token');

if (!$request_token) die("An error occurred handling the information sent back from Satisfaction.");

# TBD: refactor this, expire old entries (two weeks)
$sql = "select token_secret from oauth_tokens where token='" . 
        $request_token . "'";
$result = mysql_query($sql);
if (!$result) { die(mysql_error()); }
$cols = mysql_fetch_array($result);
$request_token_secret = $cols[0];

$oauth_req = new HTTP_Request_OAuth(
                   'http://getsatisfaction.com/api/access_token',
                   array('consumer_key' => 'lmwjv4kzwi27',
                         'token' => $request_token,
                         'token_secret' => $request_token_secret,
                         'consumer_secret' => 'fiei6iv61jnoukaq1aylwd8vcmnkafrs',
                         'signature_method' => 'HMAC-SHA1',
                         'method' => 'GET'));

$resp = $oauth_req->sendRequest(true, true);

#dump($oauth_req->getResponseBody());
list($token, $token_secret) = $oauth_req->getResponseTokenSecret();

error_log("got permanent user token: $token, $secret");

$result = mysql_query("update oauth_tokens set token = '" . $token . 
                      "', token_secret = '" . $token_secret . 
                      "' where token = '" . $request_token . "'");
if (!$result) die("Failed to store auth tokens on oauth response");

$sprink = new Sprinkles($company_id);

$sprink->open_session($token);

$return = request_param('return');

if (!$return) $return = 'helpstart.php';

redirect($return);

?>
