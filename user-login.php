<?php

try {

require_once("Sprinkles.php");

$sprink = new Sprinkles();
$return = request_param('return');
$smarty->assign('return', $return);         # FIXME: check for nastiness?

redirect($sprink->authorize_url($return, false));
exit(0);

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>
