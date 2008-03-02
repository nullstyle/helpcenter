<?php

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$from = request_param('email');
$from = preg_replace('/\n.*$/', ' ', $from);   # Sanitizes $from; it'll go verbatim in the SMTP headers

$message = request_param('name') . 
           ' reported a problem in the Get Satisfaction Instant On Help Center at ' . 
           date('H:i:s T, Y/m/d') . '.' . "\n\n" . 
           'Summary: ' . request_param('summary') . "\n\n" .
           'What I did: ' . request_param('action') . "\n\n" .
           'What I expected to happen: ' . request_param('expectation') . "\n\n" .
           'What I observed: ' . request_param('observed') . "\n\n" .
           'I\'m ' . request_param('feeling');

$subject = request_param('summary');

$contact = $sprink->site_contact_info();
$to = $contact['contact_email'];

mail($to, $subject, $message, "From: $from");

$name = request_param('name');

redirect('contactus-success.php?name=' . urlencode($name));

?>
