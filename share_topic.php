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

$smarty->assign('topic_lead', $lead_item);
$smarty->assign('topic_id', $topic_id);

# Standard stash items
$smarty->assign('background_color', $sprink->site_background_color());
$smarty->assign('company_name', $company_name);
$smarty->assign('user_name', $sprink->current_username());
$smarty->assign('current_url', 'share_topic.php');
$smarty->assign('current_user', $sprink->current_user());

$smarty->display('share_topic.t');

?>
