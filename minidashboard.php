<?
require_once('Sprinkles.php');

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$topics = array();

$smarty->assign('company_name', $company_name);
$smarty->assign('topics', $topics);
$smarty->display('minidashboard.t');
?>
