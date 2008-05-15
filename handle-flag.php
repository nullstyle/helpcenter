<?php
try {

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$type = request_param('type');
if ($type != 'topic' && $type != 'reply')
  die("unknown type '$type' while flagging");
  
$POST_URL =  $type == 'topic' ? $sprink->api_url("flagged/topics")
          : ($type == 'reply' ? $sprink->api_url("flagged/replies")
          : '');

$id = request_param('id');

$params = $type == 'topic' ? array('topic_id' => $id)
        : ($type == 'reply' ? array('reply_id' => $id)
        :  '');

$creds = $sprink->current_user_session();
if (!$creds) die("Not logged in");  # FIXME

$req = $sprink->oauthed_request('POST', $POST_URL, $creds, null, $params);

if (201 != ($responseCode = $req->getResponseCode())) {
  die("API Error $responseCode flagging item $type $id.");
}

redirect('topic.php?id=' . request_param('topic_id') . 
           ($type == 'topic' ? '&flagged_topic=' : 
           ($type == 'reply' ? '&flagged_reply=' :
           '')) . $id);

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>