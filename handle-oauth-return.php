<?php

require_once('Sprinkles.php');
require_once('HTTP_Request_Oauth.php');

$request_token = request_param('oauth_token');

if (!$request_token)
  die("An error occurred handling the information sent back from Get Satisfaction.");

# TBD: expire old entries (two weeks)
$sql = "select token_secret from sessions where token='" . $request_token . "'";
$result = mysql_query($sql);
if (!$result) { die(mysql_error()); }
$cols = mysql_fetch_array($result);
$request_token_secret = $cols[0];

$sprink = new Sprinkles();
$consumer_data = $sprink->oauth_consumer_data();

list($token, $token_secret) = get_oauth_access_token($consumer_data,
                                                     $request_token,
                                                     $request_token_secret);

$result = mysql_query("update sessions set token = '" . $token . 
                      "', token_secret = '" . $token_secret . 
                      "' where token = '" . $request_token . "'");
if (!$result) die("Failed to store auth tokens on oauth response");

$sprink = new Sprinkles();

$sprink->open_session($token);

if (!$sprink->site_configured() && request_param('first_login')) {
  $user = $sprink->current_user();
  if (!$user) die("Internal error: No current user just after opening session.");
  $sprink->set_admin_users(array($user['canonical_name']));
}

$return = request_param('return');

if (!$return) $return = 'helpstart.php';

redirect($return);

?>