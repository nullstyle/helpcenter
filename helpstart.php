<?php
try { 

require_once("Sprinkles.php");

$sprink = new Sprinkles();

$top_topic_tags = take(20, 
                    $sprink->tags($api_root . 'companies/' . 
                    $sprink->company_sfnid . 
                    '/tags?on=topics&sort=usage&limit=20'));

$chunk = intval(ceil(sizeof($top_topic_tags)/4));

$top_topic_tags = array_chunk($top_topic_tags, $chunk);

$entries = $sprink->topics(array("limit" => $helpstart_topic_count, "sort" => 'recently_active'));
$sprink->resolve_authors($entries['topics']);

$smarty->assign('top_topic_tags', $top_topic_tags);
$smarty->assign('entries', $entries['topics']);

# Standard stash items
$smarty->assign('products', $sprink->product_list());
$smarty->assign('current_url', 'helpstart.php');
$smarty->assign('totals', $entries['totals']);
$smarty->assign('filter_style', 'question');
$sprink->add_std_hash_elems($smarty);
$smarty->display('helpstart.t');

finish_request('helpstart');

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->assign('error_msg', $e->getMessage());
  $smarty->display('error.t');
}

?>
