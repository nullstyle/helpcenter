<?
require("class.myatomparser.php");
require_once("Sprinkles.php");

$h = new hkit;

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$entries = $sprink->topics(array());
$entries = take($helpstart_topic_count, $entries);

$smarty->assign('company_name', $company_name);
$smarty->assign('body_css_id', 'contactus');
$smarty->display('contactus.t');

?>
