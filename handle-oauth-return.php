<?php
try {

require_once('Sprinkles.php');
require_once('HTTP_Request_Oauth.php');

$request_token = request_param('oauth_token');

if (!$request_token)
  throw new Exception("No OAuth request token was present in the return from Get Satisfaction.");

# TBD: expire old entries (two weeks)
$sql = "select token_secret from sessions where token='" .
        mysql_real_escape_string($request_token) . "'";
$result = mysql_query($sql);
if (!$result) {
  throw new Exception("Couldn't look up token $request_token; database error: "
                      . mysql_error());
}
$cols = mysql_fetch_array($result);
$request_token_secret = $cols[0];

$sprink = new Sprinkles();
$consumer_data = $sprink->oauth_consumer_data();

list($token, $token_secret) = get_oauth_access_token($consumer_data,
                                                     $request_token,
                                                     $request_token_secret);

if (!$token || !$token_secret) {
  throw new Exception("Getting OAuth access token from Get Satisfaction failed.");
}

$result = mysql_query("update sessions set token = '" . mysql_real_escape_string($token) . 
                      "', token_secret = '" . mysql_real_escape_string($token_secret) . 
                      "' where token = '" . mysql_real_escape_string($request_token) . "'");
if (!$result) throw new Exception("Failed to store auth tokens on oauth response");

$sprink = new Sprinkles();

$sprink->open_session($token);
message($sprink->site_configured());

if (!$sprink->site_configured() && request_param('first_login')) {
  $user = $sprink->current_user();
  if (!$user) throw new Exception("Internal error: No current user just after opening session.");
  $sprink->set_admin_users(array($user['canonical_name']));
  $result = $sprink->set_site_settings(array('configured' => 'Y'));
  if (!$result) die (mysql_error());
}

$return = request_param('return');

if (!$return) $return = 'helpstart.php';

redirect($return);

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>