<?
require_once("config.php");
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$topic_id = request_param('id');
if (!$topic_id) die("Internal error: expected id parameter.");
$items = $sprink->topic($topic_id);

$lead_item = array_shift($items);

$smarty->assign('background_color', $sprink->site_background_color());
$smarty->assign('company_name', $company_name);
$smarty->assign('topic_lead', $lead_item);
$smarty->assign('topic_id', $topic_id);

$smarty->display('share_topic.t');

?>
