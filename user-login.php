<?
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$smarty->assign('background_color', $sprink->site_background_color());
$smarty->assign('company_name', $company_name);
$return = request_param('return');
# assert($return);
$smarty->assign('return', $return); # FIXME: check for nastiness?


$smarty->display('user-login.t');

?>
