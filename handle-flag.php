<?php

require_once('config.php');
require_once('Sprinkles.php');

$sprink = new Sprinkles();

# There are a set of resources underneath /flagged:
# 
# http://api.getsatisfaction.com/flagged/topics
# http://api.getsatisfaction.com/flagged/replies
# http://api.getsatisfaction.com/flagged/products
# http://api.getsatisfaction.com/flagged/people
# http://api.getsatisfaction.com/flagged/companies
# 
# You would POST to these to flag an item.  you would provide the following parameters:
# 
# topic_id  		REQUIRED	#=> more specificially, the singular id form of the item to be flagged:  reply_id, product_id, person_id, etc.
# flag[name]		OPTIONAL	#=> This is the radio button field "Inappropriate", "Spam", etc.
# flag[description]	OPTIONAL	#=> Free form text the user enters

$type = request_param('type');
if ($type != 'topic' && $type != 'reply')
  die("unknown type '$type' while flagging");
$POST_URL =  $type == 'topic' ? $sprink->api_url("flagged/topics")
          : ($type == 'reply' ? $sprink->api_url("flagged/replies")
          : '');

$id = request_param('id');

error_log("Using $POST_URL to flag $id");

$params = $type == 'topic' ? array('topic_id' => $id)
        : ($type == 'reply' ? array('reply_id' => $id)
        :  '');

$creds = $sprink->current_user_creds();
if (!$creds) die("not logged in");  # FIXME

$req = $sprink->oauthed_request('POST', $POST_URL, $creds, null, $params);

if (201 != ($responseCode = $req->getResponseCode())) {
#  error_log($req->getResponseBody());
  die("API Error $responseCode flagging item $type $id.");
}

redirect('topic.php?id=' . request_param('topic_id'));

?>