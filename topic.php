<? 
require_once("setup.php");
require_once("Sprinkles.php");
require_once("class.myatomparser.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$topic_id = $_GET['id'];
$topic_url = $quick_mode ?
     $cache_dir . 'topics/40621.cache' : 
     $topic_id;
assert(!!$topic_url);

$topic_feed = new myAtomParser($topic_url);

$topic_updated = $topic_feed->output["FEED"][""]["UPDATED"];

$items = array();
foreach ($topic_feed->output["FEED"][""]["ENTRY"] as $entry) {
  $item = array();
	# FIXME watch out for format changes here
  $item["AUTHOR"] = $entry["AUTHOR"];
  $person = $sprink->get_person($item["AUTHOR"]["URL"]);

  $item["AUTHOR"]["PHOTO"] = $person[0]["photo"];
  $item["TITLE"] = $entry["TITLE"];
  $item["ID"] = $entry["ID"];
  $item["CONTENT"] = $entry["CONTENT"];
  $item["UPDATED"] = $entry["UPDATED"];
  $item["UPDATED_EPOCH"] = strtotime($entry["UPDATED"]);
  $item["UPDATED_RELATIVE"] = ago(strtotime($entry["UPDATED"]), time());
  $item["TOPIC_STYLE"] = ago(strtotime($entry["SFN:TOPIC_STYLE"]), time());
  array_push($items, $item);
}

#$smarty->assign('topic_updated', $topic_updated);
#$smarty->assign('topic_updated_relative', ago(strtotime($topic_updated), time()));
$smarty->assign(array('topic_updated' => $topic_updated,
                      'topic_updated_relative' =>
                           ago(strtotime($topic_updated), time())));

$smarty->assign('company_name', $company_name);
$smarty->assign('test', array('foo' => array('baz' => 'bonk')));
$smarty->assign('items', $items);
$smarty->assign('lead', array_shift($items));
$smarty->assign('replies', $items);

$smarty->display('topic.t');
?>
