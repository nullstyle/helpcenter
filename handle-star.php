<?php

require_once('config.php');
require_once('Sprinkles.php');

$sprink = new Sprinkles();

$id = request_param('reply_id');

$POST_URL = $sprink->api_url($id . "/stars");

error_log("Using $POST_URL to star $id");

$params = array();
$params = array('reply_id' => $id);

$creds = $sprink->current_user_creds();
if (!$creds) die("not logged in");      # FIXME

$req = $sprink->oauthed_request('POST', $POST_URL, $creds, null, $params);

if (400 == ($responseCode = $req->getResponseCode())) { # TBD: refine this to read HTTP reason
  redirect('topic.php?no_self_star=1&id=' . request_param('topic_id'));
  exit(0);
}

if (201 != $responseCode) {
  error_log("Failed starring with POST to $POST_URL: " . $req->getResponseBody());
  die("API Error $responseCode starring reply $id.");
}

redirect('topic.php?id=' . request_param('topic_id'));

?>

<?php


?>
