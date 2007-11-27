<?
require_once("class.myatomparser.php");
require_once("setup.php");
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$entries = $sprink->topics();
$entries = take($helpstart_topic_count, $entries);

$company_people = $sprink->people();
assert(is_array($company_people));

$smarty->assign('company_people', $company_people);
$smarty->assign('company_name', $company_name);
$smarty->assign('entries', $entries);

$smarty->display('helpstart.t');

?>
