<?php

require_once("Sprinkles.php");

$sprink = new Sprinkles();

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$smarty->assign('return', request_param('return'));         # FIXME: check for nastiness?

$smarty->assign('login_page', true);

$smarty->assign('current_url', 'user-login.php');

$sprink->add_std_hash_elems($smarty);

$smarty->display('user-login.t');

?>
