<?php
try {

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$sprink->close_admin_session();

redirect('logged-out.php');

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}
?>
