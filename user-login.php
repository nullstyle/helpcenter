<?

require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$return = request_param('return');
# assert($return);
$smarty->assign('return', $return); # FIXME: check for nastiness?

$smarty->assign('login_page', true);

# Standard stash items
$smarty->assign('background_color', $sprink->site_background_color());
$smarty->assign('company_name', $company_name);
$smarty->assign('current_url', 'user-login.php');
$smarty->assign('username', $sprink->current_user() ? 'TBD' : '');

$smarty->display('user-login.t');

?>
