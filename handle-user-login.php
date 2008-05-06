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

list($token, $secret) = get_oauth_request_token($consumer_data);

if (!$token || !$secret) {
  error("Failed to fetch OAuth request token " . 
        "(Result token: '$token'; Token secret: '$token_secret')");
  die("Failed to fetch OAuth request token from getsatisfaction.com.");
}

$result = insert_into('sessions', array('token' => $token,
                                        'token_secret' => $secret));
if (!$result) die("Error inserting OAuth tokens into database.");

$first_login = request_param('first_login');

$callback_url = $sprink->sprinkles_root_url() . 'handle-oauth-return.php?' . 
                  ($first_login ? 'first_login=true&': '') .
                  'return=' . urlencode($return);

redirect(oauth_authorization_url($token, $callback_url));

?>