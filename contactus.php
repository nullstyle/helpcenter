<?
require_once("Sprinkles.php");

$h = new hkit;

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$entries = $sprink->topics(array());
$entries = take($helpstart_topic_count, $entries);

$smarty->assign('body_css_id', 'contactus');
$smarty->assign('current_url', 'contactus.php');

$sprink->add_std_hash_elems($smarty);

$smarty->display('contactus.t');

?>
