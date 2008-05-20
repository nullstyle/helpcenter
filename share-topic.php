<?php
try {

require_once("Sprinkles.php");

$sprink = new Sprinkles();

$topic_id = request_param('id');
if (!$topic_id) die("Internal error: expected id parameter.");
$items = $sprink->topic($topic_id);
if (!$items) die("Internal error: Empty topic $topic_id.");

$topic_head = array_shift($items['replies']);


$smarty->assign('topic_head', $topic_head);
$smarty->assign('topic_id', $topic_id);
$smarty->assign('body_css_id', 'share-topic');

$smarty->assign('current_url', 'share-topic.php?id=' . $topic_id);

$sprink->add_std_hash_elems($smarty);
$smarty->display('share-topic.t');

finish_request('share-topic');

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>
