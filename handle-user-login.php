<?php

require_once('Sprinkles.php');
require_once('HTTP_Request_Oauth.php');

$sprink = new Sprinkles();

$return = request_param('return');

$consumer_data = $sprink->oauth_consumer_data();
if (!$consumer_data['key'] || !$consumer_data['secret']){
  die("The OAuth consumer data was missing from the Instant-On Help " . 
      "Center database! Perhaps something went wrong during installation " . 
      "and setup.");
}
$oauth_req = new HTTP_Request_OAuth(
                   'http://getsatisfaction.com/api/request_token',
                   array('consumer_key' => $consumer_data['key'],
                         'consumer_secret' => $consumer_data['secret'],
                         'signature_method' => 'HMAC-SHA1',
                         'method' => 'GET'));

$resp = $oauth_req->sendRequest(true, true);

list($token, $secret) = $oauth_req->getResponseTokenSecret();

if (!$token || !$secret) {
  error("Failed to fetch OAuth request token " . 
        "(Result token: '$token'; Token secret: '$token_secret')");
  die("Failed to fetch OAuth request token from getsatisfaction.com.");
}

$result = mysql_query('insert into sessions (token, token_secret) values (\''
                      . $token . '\', \'' . $secret . '\')');

if (!$result) die("Error inserting OAuth tokens into database.");

$first_login = request_param('first_login');

$callback_url = sprinkles_root_url() . 'handle-oauth-return.php?' . 
                  ($first_login ? 'first_login=true&': '') .
                  'return=' . urlencode($return);

# FIXME: hardcoded API URL
$url = 'http://getsatisfaction.com/api/authorize?oauth_token='. $token
         . '&oauth_callback=' . urlencode($callback_url);

redirect($url);

?>