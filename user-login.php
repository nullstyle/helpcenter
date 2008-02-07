<?php

require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$return = request_param('return');
# assert($return);
$smarty->assign('return', $return); # FIXME: check for nastiness?

$smarty->assign('login_page', true);

# Standard stash items
$smarty->assign('current_url', 'user-login.php');

$sprink->add_std_hash_elems($smarty);

$smarty->display('user-login.t');

?>
