<?
require("class.myatomparser.php");
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
# dump($company_hcard);
$company_name = $company_hcard["fn"];


$topics = $sprink->topics();
$topics = take($discuss_topic_count, $topics);
$topic_count = count($topics);  

foreach ($topics as &$topic) {
  $topic["REPLY_COUNT"] = $topic["SFN:REPLY_COUNT"];
  if (!($topic["REPLY_COUNT"] > 0)) 
    $topic["REPLY_COUNT"] = 0;
  $topic["TOPIC_STYLE"] = $topic["SFN:TOPIC_STYLE"];
}

$smarty->assign('products', $sprink->products());
$smarty->assign('company_name', $company_name);
$smarty->assign('topics', $topics);
$smarty->assign('topic_count', $topic_count);
$smarty->display('discuss.t');
?>
