<?php

require_once('config.php');
require_once('Sprinkles.php');

$subject = request_param('subject');
$details = request_param('details');
$tags = request_param('tags');
$face = request_param('emoticon');
$emotion = request_param('emotion');
$products = request_param('product');
if (!$products) $products = array();
$products_commasep = join(',', $products);

$sprink = new Sprinkles();

$POST_URL = 'http://api.getsatisfaction.com/topics';   # FIXME: hard-coded API URL

$creds = $sprink->current_user_creds();

$req = $sprink->oauthed_request('POST', $POST_URL, $creds, null, 
                    array('topic[company_domain]' => $sprink->company_sfnid,
                              # safeguard for now; FIXME when we go live
                          'topic[subject]' => $subject,
                          'topic[additional_detail]' => $details,
                          'topic[keywords]' => $tags,
                          'topic[products]' => $products_commasep,
                          'topic[emotitag][face]' => $face,
                          'topic[emotitag][emotion]' => $emotion
));

$response_body = $req->getResponseBody();

try {
  $topic_feed = new XML_Feed_Parser($response_body);
} catch (Exception $e) {
  error_log("Failed to post new topic; response was: " . $req->getResponseCode() . 
            ", body: " . $response_body);
  die("Posting the topic failed.");
}

if ($topic_feed->id()) {     # FIXME: better error checking here.
  redirect('topic.php?id=' . $topic_feed->id());
} else {
  print "An error occured";
}

?>
