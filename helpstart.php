<?php
try { 

require_once("Sprinkles.php");

$sprink = new Sprinkles();

$top_topic_tags = take(20, 
                    $sprink->tags('http://api.getsatisfaction.com/companies/' . 
                    $sprink->company_sfnid . 
                    '/tags?on=topics&sort=usage&limit=20'));


$entries = $sprink->topics(array("limit" => $helpstart_topic_count, "sort" => 'recently_active'));
$sprink->resolve_authors($entries['topics']);

$topics = $sprink->topics(array("limit" => "1"));

$smarty->assign('top_topic_tags', $top_topic_tags);
$smarty->assign('entries', $entries['topics']);

# Standard stash items
$sprink->add_std_hash_elems($smarty);
$smarty->assign('current_url', 'helpstart.php');
$smarty->assign('totals', $topics['totals']);
$smarty->display('helpstart.t');

finish_request('helpstart');

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->assign('error_msg', $e->getMessage());
  $smarty->display('error.t');
}

?>
