<?php
try {

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$sprink->close_session();

$return = request_param('return');
if (!$return) $return = 'helpstart.php';

redirect($return);

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>
