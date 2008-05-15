<?php
try {

require_once('Sprinkles.php');


$subject = request_param('subject');
$details = request_param('details');
$tags = request_param('tags');
$face = request_param('emoticon');
$emotion = request_param('emotion');
$style = request_param('style');
$products = request_param('product');

if (!$products) $products = array();
$products_commasep = join(',', $products);

$sprink = new Sprinkles();

$creds = $sprink->current_user_session();
if (!$creds) {
  $target_page = $preview_after_login                   # setting in config.php
                   ? 'submit.php' : 'handle-submit.php';
  $args = 'subject=' . urlencode($subject) .
          '&details=' . urlencode($details) .
          '&tags=' . urlencode($tags) .
          '&emoticon=' . urlencode($face) .
          '&emotion=' . urlencode($emotion) .
          '&style=' . urlencode($style);
  foreach ($products as $product)
    $args .= '&product[]=' . urlencode($product);
  redirect('user-login.php?return=' .
           urlencode($target_page . '?' . $args));
}

$POST_URL = $api_root . 'companies/'. $sprink->company_sfnid .'/topics';

$req = $sprink->oauthed_request('POST', $POST_URL, $creds, null, 
                    array('topic[subject]' => $subject,
                          'topic[additional_detail]' => $details,
                          'topic[style]' => $style,
                          'topic[keywords]' => $tags,
                          'topic[products]' => $products_commasep,
                          'topic[emotitag][face]' => $face,
                          'topic[emotitag][feeling]' => $emotion
));

$response_body = $req->getResponseBody();

try {
  $topic_feed = new XML_Feed_Parser($response_body);
} catch (Exception $e) {
  error("Failed to post new topic; response was: " . $req->getResponseCode() . 
        ", body: " . $response_body);
  var_dump($response_body);
  die("Posting the topic failed.");
}

if ($topic_feed->id()) {     # FIXME: better error checking here.
  redirect('topic.php?id=' . $topic_feed->id());
} else {
  print "An error occured";
}

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}
?>
