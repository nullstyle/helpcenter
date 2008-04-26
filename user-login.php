<?php

require_once("Sprinkles.php");

$sprink = new Sprinkles();

$smarty->assign('return', request_param('return'));         # FIXME: check for nastiness?

$smarty->assign('login_page', true);

$smarty->assign('current_url', 'user-login.php');

$sprink->add_std_hash_elems($smarty);

$smarty->display('user-login.t');

finish_request('user-login');

?>
