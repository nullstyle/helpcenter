<?php

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$id = request_param('id');
$topic = $sprink->topic($id);
$topic = $topic['replies'][0];

$from = request_param('from_email');
$from = preg_replace('/\n.*$/', ' ', $from);   # Sanitizes $from; it'll go verbatim in the SMTP headers

$user_fn = request_param('sender_name');
if (!$user_fn) {
  $user = $sprink->current_user();
  $user_fn = $user['fn'];
}

$personal_message = request_param('personal_message');

$message = $user_fn . " thinks you might be interested in this discussion from Get Satisfaction:\n\n" .
           "\"" . $topic['title'] . "\n\n" .
           $topic['content'] . "\"\n\n" . 
           $topic['author']['fn'] . " asked this on " . $topic['published_formatted'] .
           (!$personal_message ? '' :
            "\n\n" . $user_fn . " says: \n\n" . $personal_message
           );

$subject = "'" . $topic['title'] . "' on Get Satisfaction!";

$to = request_param('to_email');     # TBD: handle more than one address
if (preg_match('/,/', $to))
  die("Sharing with more than one recipient is not yet implemented. :-(");

error_log("Attempting to send email '$subject' from $from to $to with body $message");
$result = mail($to, $subject, $message, "From: $from");
if ($result) {
  redirect('topic.php?id=' . $id . '&shared_with=' . urlencode($to));
} else {
  redirect('topic.php?id=' . $id . '&share_failed=true');
}

?>
