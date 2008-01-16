<?
require_once("config.php");
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$entries = $sprink->topics(array());
$entries['topics'] = take($helpstart_topic_count, $entries['topics']);

$company_people = $sprink->employees();
assert(is_array($company_people));

$smarty->assign('contact_info', $sprink->site_contact_info());
$smarty->assign('background_color', $sprink->site_background_color());
$smarty->assign('company_people', $company_people);
$smarty->assign('company_name', $company_name);
$smarty->assign('entries', $entries['topics']);

$smarty->display('helpstart.t');
?>
