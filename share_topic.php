<?
require_once("config.php");
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$topic_id = request_param('id');
if (!$topic_id) die("Internal error: expected id parameter.");
$items = $sprink->topic($topic_id);

$topic_head = array_shift($items);

$smarty->assign('topic_head', $topic_head);
$smarty->assign('topic_id', $topic_id);

$smarty->assign('current_url', 'share_topic.php');

$sprink->add_std_hash_elems($smarty);

$smarty->display('share_topic.t');

?>
