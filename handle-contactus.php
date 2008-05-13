<?php
try {
require_once('Sprinkles.php');

$sprink = new Sprinkles();

$from = request_param('email');
$from = preg_replace('/\n.*$/', ' ', $from);   # Sanitizes $from; it'll go verbatim in the SMTP headers

$message = request_param('name') . 
           ' reported a problem in the Get Satisfaction Instant On Help Center at ' . 
           date('H:i:s T, Y/m/d') . '.' . "\n\n" . 
           'Summary: ' . request_param('summary') . "\n\n" .
           'Details: ' . request_param('observed') . "\n\n" .
           'I\'m ' . request_param('feeling');

$subject = request_param('summary');

$contact = $sprink->site_contact_info();
$to = $contact['contact_email'];

mail($to, $subject, $message, "From: $from");

$name = request_param('name');

redirect('contactus-success.php?name=' . urlencode($name));

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}
?>
