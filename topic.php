<?php
try {

require_once("config.php");
require_once("Sprinkles.php");

$sprink = new Sprinkles();

$topic_id = request_param('id');
if (!$topic_id) {
  $topic_id = request_param('topic_id');
}
if (!$topic_id) {
  $sfn_id = request_param('sfn_id');
  $topic_id = $sprink->api_url("topics/" . $sfn_id);
}
if (!$topic_id) die("Internal error: expected id parameter.");

$topic = $sprink->topic($topic_id);
$topic_head = array_shift($topic['replies']);
$reply_count = count($topic['replies']);
$topic['replies'] = thread_items($topic['replies'], $topic_head['id']);
$toplevel_reply_count = count($topic['replies']);

$topic['replies'] = flatten_threads($topic['replies']);

$sprink->resolve_author($topic_head);
$sprink->resolve_authors($topic['replies']);

$related_topics = $sprink->topics(array('related' => $topic_id));
list($company_related_topics, $noncompany_related_topics) = 
       $sprink->company_partition($related_topics['topics']);
$noncompany_related_topics = 
                  take($related_topics_count, $noncompany_related_topics);
resolve_companies($noncompany_related_topics);

list($company_promoted, $star_promoted) = filter_promoted($topic['replies']);

$smarty->assign('topic_head', $topic_head);
$smarty->assign('replies', $topic['replies']);
$smarty->assign('related_topics', $noncompany_related_topics);
$smarty->assign('particip', $topic['particip']);
$smarty->assign('tags', $topic['tags']);
$smarty->assign(array('reply_count' => $reply_count,
                      'toplevel_reply_count' => $toplevel_reply_count));
$smarty->assign('num_pages', ceil($toplevel_reply_count/$topic_page_size));
$smarty->assign('page_num', $page_num);
$smarty->assign('topic_id', $topic_id);
$smarty->assign('reply_url', $reply_url);
$smarty->assign(array('company_promoted_replies' => $company_promoted,
                      'star_promoted_replies' => $star_promoted));
$smarty->assign('flagged_topic', request_param('flagged_topic'));
$smarty->assign('own_topic', $topic_head['author']['canonical_name']
                             == $sprink->current_username());
$smarty->assign('flagged_reply', request_param('flagged_reply'));
if (request_param('shared_with'))
  $smarty->assign('shared_with', explode(',', request_param('shared_with')));
if (request_param('share_failed'))
  $smarty->assign('share_failed_msg', true);
if (request_param('me_tood_topic'))
  $smarty->assign('me_tood_topic_msg', true);
if (request_param('me_too_failed'))
  $smarty->assign('me_too_failed_error', true);
if (request_param('no_self_star'))
  $smarty->assign('self_star_error', true);
if (request_param('blank_reply'))
  $smarty->assign('blank_reply_error', true);

$smarty->assign('current_url', 'topic.php?id=' . $topic_id);

$sprink->add_std_hash_elems($smarty);

$smarty->display('topic.t');

finish_request('topic');

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>