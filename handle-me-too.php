<?php

# POST to /topics/$sfn_id/me_toos

require_once('config.php');
require_once('Sprinkles.php');

$sprink = new Sprinkles();

$sfn_id = request_param('sfn_id');

$POST_URL = $sprink->api_url("topics/" . $sfn_id . "/me_toos");

# error_log("Using $POST_URL to me-too $sfn_id");

$creds = $sprink->current_user_session();
if (!$creds) die("You are not logged in.");  # FIXME

$req = $sprink->oauthed_request('POST', $POST_URL, $creds, null, array());

$responseCode = $req->getResponseCode();
if (400 == $responseCode) {
  redirect('topic.php?sfn_id=' . $sfn_id . 
           '&me_too_failed=true');

  die("You have already marked this topic as \"me too,\""
      . " or it is your own topic.");                      # FIXME: nicer error
} else if (201 != $responseCode) {
#  error_log($req->getResponseBody());
  die("API Error $responseCode me-tooing topic $sfn_id.");
}

$topic_url = $sprink->api_url("topics/" . $sfn_id);
invalidate_http_cache($topic_url);

redirect('topic.php?sfn_id=' . $sfn_id . 
         '&me_tood_topic=true');

?>