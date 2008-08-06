<?php
try {

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$topic_id = request_param('topic_id');
$reply_url = request_param('replies_url');

if (!($content = request_param('content'))) {
  redirect('topic.php?blank_reply=1&id=' . urlencode($topic_id));
  exit(0);
}

$params = array('reply[content]' => $content);
if ($parent_id = request_param('parent_id'))
  $params['reply[parent_id]'] = $parent_id;

$creds = $sprink->current_user_session();
if (!$creds)
  die("Not logged in! (FIXME)");
$req = $sprink->oauthed_request('POST', $reply_url, $creds, null, $params);

if (201 != ($responseCode = $req->getResponseCode())) {
  die("API Error $responseCode replying to $topic_id.");
}

$topic_url = $topic_id;
invalidate_http_cache($topic_url);

redirect('topic.php?id=' . urlencode($topic_id));
exit(0);

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>