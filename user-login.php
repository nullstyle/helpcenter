<?php

try {

require_once("Sprinkles.php");

$sprink = new Sprinkles();

$smarty->assign('return', request_param('return'));         # FIXME: check for nastiness?

redirect($sprink->authorize_url($return, false));

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>
