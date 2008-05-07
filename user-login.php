<?php

require_once("Sprinkles.php");

$sprink = new Sprinkles();

$smarty->assign('return', request_param('return'));         # FIXME: check for nastiness?

redirect($sprink->authorize_url($return, false));

?>
