<?
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$smarty->assign('background_color', $sprink->site_background_color());
$smarty->assign('company_name', $company_name);

$smarty->display('user-login.t');

?>
