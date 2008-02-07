<?php

require_once('config.php');
require_once('Sprinkles.php');

$sprink = new Sprinkles();

$content = request_param('content');
$topic_id = request_param('topic_id');

$POST_URL = $topic_id. '/replies'; # TBD: use @rel=replies link, when implemented server-side.

$params = array('reply[content]' => $content);
if ($parent_id = request_param('parent_id'))
  $params['reply[parent_id]'] = $parent_id;

$creds = $sprink->current_user_creds();
$req = $sprink->oauthed_request('POST', $POST_URL, $creds, null, $params);

# FIXME: check for errors
error_log($req->getResponseBody());

redirect('topic.php?id=' . $topic_id);

?>