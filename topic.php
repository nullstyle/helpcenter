<? 
require("setup.php");
require("utils.php");
require("class.myatomparser.php");
require_once('hkit.class.php');
$h = new hKit;

# TBD: pull this into utils.php
$company_url = api_url('companies/'.$company_id);
if ($quick_mode) {
  $company_hcard = $h->getByString('hcard', file_get_contents($company_url));
} else {
  $company_hcard = $h->getByURL('hcard',$company_url);
}

# dump($company_hcard);
$company_name = $company_hcard[0]["fn"];

$topic_id = $_GET['id'];
$topic_url = $quick_mode ?
     $cache_dir . 'topics/40621.cache' : 
     $topic_id;
assert(!!$topic_url);

$topic_feed = new myAtomParser($topic_url);
# dump($topic_feed->output);

$items = array();
foreach ($topic_feed->output["FEED"][""]["ENTRY"] as $entry) {
  $item = array();
	# FIXME watch out for format changes here
  $item["AUTHOR"] = $entry["AUTHOR"];
#  if ($quick_mode) {
#    $person = $h->getByString('hcard', file_get_contents($cache_dir . "people-40451.html"));
#  } else {
    $person = $h->getByURL('hcard', $item["AUTHOR"]["URL"]);
#  }
  # dump($person);
  $item["AUTHOR"]["PHOTO"] = $person[0]["photo"];
  $item["TITLE"] = $entry["TITLE"];
  $item["ID"] = $entry["ID"];
  $item["CONTENT"] = $entry["CONTENT"];
  $item["UPDATED"] = $entry["UPDATED"];
  $item["UPDATED_EPOCH"] = strtotime($entry["UPDATED"]);
  $item["UPDATED_RELATIVE"] = ago(strtotime($entry["UPDATED"]), time());
  array_push($items, $item);
}

$smarty->assign('company_name', $company_name);
$smarty->assign('test', array('foo' => array('baz' => 'bonk')));
$smarty->assign('items', $items);
$smarty->assign('lead', array_shift($items));
$smarty->assign('replies', $items);

$smarty->display('topic.t');
?>
