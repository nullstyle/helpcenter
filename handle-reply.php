<?php

require_once('config.php');
require_once('Sprinkles.php');

$sprink = new Sprinkles();

$content = request_param('content');
$topic_id = request_param('topic_id');

$POST_URL = $topic_id. '/replies'; # TBD: use @rel=replies link, when implemented server-side.

$creds = $sprink->current_user_creds();
$req = $sprink->oauthed_request('POST', $POST_URL, $creds, null, 
                                array('reply[content]' => $content));

# FIXME: check for errors
#error_log($req->getResponseBody());

redirect('topic.php?id=' . $topic_id);

?>