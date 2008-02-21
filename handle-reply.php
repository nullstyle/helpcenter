<?php

require_once('config.php');
require_once('Sprinkles.php');

$sprink = new Sprinkles();

$topic_id = request_param('topic_id');

$reply_url = request_param('reply_url');
# error_log("reply on $topic_id going to $reply_url");
$reply_url = $topic_id . '/replies'; # FIXME: use reply_url from feed data.

if (!($content = request_param('content')))
  redirect('topic.php?blank_reply=1&id=' . urlencode($topic_id));

$params = array('reply[content]' => $content);
if ($parent_id = request_param('parent_id'))
  $params['reply[parent_id]'] = $parent_id;

$creds = $sprink->current_user_creds();
$req = $sprink->oauthed_request('POST', $reply_url, $creds, null, $params);

if (201 != ($responseCode = $req->getResponseCode())) {
#  error_log($req->getResponseBody());
  die("API Error $responseCode replying to $topic_id.");
}

redirect('topic.php?id=' . urlencode($topic_id));

?>