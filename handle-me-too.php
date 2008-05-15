<?php
try {

# POST to /topics/$sfn_id/me_toos

require_once('config.php');
require_once('Sprinkles.php');

$sprink = new Sprinkles();

$sfn_id = request_param('sfn_id');

$creds = $sprink->current_user_session();
if (!$creds) {
  $target_page = $preview_after_login                   # setting in config.php
                   ? 'topic.php' : 'handle-me-too.php';
  $args = 'sfn_id=' . urlencode($sfn_id);
  redirect('user-login.php?return=' .
           urlencode($target_page . '?' . $args));
}

$POST_URL = $sprink->api_url("topics/" . $sfn_id . "/me_toos");
$req = $sprink->oauthed_request('POST', $POST_URL, $creds, null, array("askdjnaksjdbas" => "aksjhdaksjdnaksjdnka"));

$responseCode = $req->getResponseCode();
if (0 == $responseCode) {
  die("Timeout accessing the API, while posting to $POST_URL."); # FIXME: recover for user
} else if (400 == $responseCode) {
  redirect('topic.php?sfn_id=' . $sfn_id . 
           '&me_too_failed=true');
} else if (201 != $responseCode) {
  die("API Error $responseCode me-tooing topic $sfn_id.");
}

$topic_url = $sprink->api_url("topics/" . $sfn_id);
invalidate_http_cache($topic_url);

redirect('topic.php?sfn_id=' . $sfn_id . 
         '&me_tood_topic=true');

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>