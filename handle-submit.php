<?php

require_once('config.php');
require_once('Sprinkles.php');

$subject = request_param('subject');
$details = request_param('details');
$tags = request_param('tags');
$face = request_param('emoticon');

$sprink = new Sprinkles();

$POST_URL = 'http://api.getsatisfaction.com/topics';

$creds = $sprink->current_user_creds();

$req = $sprink->oauthed_request('POST', $POST_URL, $creds, null, 
                    array('topic[company_domain]' => 'sprinklestestcompany',
                              # safeguard for now; FIXME when we go live
                          'topic[subject]' => $subject,
                          'topic[additional_detail]' => $details,
                          'topic[keywords]' => $tags
                          #, 'topic[emoticon][face]' => $face
));

# TBD: On a 401, expire the token.

$topic_feed = new XML_Feed_Parser($req->getResponseBody());

if ($topic_feed->id()) {     # FIXME: better error checking here.
  redirect('topic.php?id=' . $topic_feed->id());
} else {
  print "An error occured";
}

?>
