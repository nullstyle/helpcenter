<?php
try {

require_once('config.php');
require_once('Sprinkles.php');

$sprink = new Sprinkles();

$reply_id = request_param('reply_id');
$topic_id = request_param('topic_id');

$creds = $sprink->current_user_session();
if (!$creds) {
  $target_page = $preview_after_login                   # setting in config.php
                   ? 'topic.php' : 'handle-star.php';
  $args = 'reply_id=' . urlencode($reply_id) .
          '&topic_id=' . urlencode($topic_id);
  redirect('user-login.php?return=' .
           urlencode($target_page . '?' . $args));
}

$POST_URL = $sprink->api_url($reply_id . "/stars");     # FIXME use @rel=stars link from feed

$params = array('reply_id' => $reply_id);

$req = $sprink->oauthed_request('POST', $POST_URL, $creds, null, $params);

if (400 == ($responseCode = $req->getResponseCode())) { # TBD: refine this to read HTTP reason
  redirect('topic.php?no_self_star=1&id=' . $topic_id);
  exit(0);
}

if (201 != $responseCode) {
  error("Failed starring with POST to $POST_URL: " . $req->getResponseBody());
  die("API Error $responseCode starring reply $reply_id.");
}

$topic_url = request_param('topic_id');
invalidate_http_cache($topic_url);

redirect('topic.php?id=' . urlencode($topic_url));

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>