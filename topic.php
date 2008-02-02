<? 
require_once("config.php");
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$page_limit = 5;

$page_num = request_param('page');
if (!$page_num) { $page_num = 0; }

$topic_id = request_param('id');
if (!$topic_id) die("Internal error: expected id parameter.");

$topic = $sprink->topic($topic_id);
$lead_item = array_shift($topic['replies']);
$reply_count = count($topic['replies']);
$topic['replies'] = $sprink->thread_items($topic['replies'], $lead_item['id']);
$toplevel_reply_count = count($topic['replies']);


$topic['replies'] = take_range($page_num * $page_limit,
                               ($page_num + 1) * $page_limit,
                               $topic['replies']);
$topic['replies'] = $sprink->flatten_threads($topic['replies']);

$related_topics = $sprink->topics(array('related' => $topic_id,
                                        'notags' => true # speeds things up
                                        ));
list($company_related_topics, $noncompany_related_topics) = 
   $sprink->company_partition($related_topics['topics']);
$noncompany_related_topics = 
                  take($related_topics_count, $noncompany_related_topics);
$sprink->resolve_companies($noncompany_related_topics);

list($company_promoted, $star_promoted) = $sprink->filter_promoted($topic['replies']);

$smarty->assign('lead_item', $lead_item);
$smarty->assign('replies', $topic['replies']);
$smarty->assign('related_topics', $noncompany_related_topics);
$smarty->assign('particip', $topic['particip']);
$smarty->assign('tags', $topic['tags']);
$smarty->assign(array('reply_count' => $reply_count,
                      'toplevel_reply_count' => $toplevel_reply_count));
$smarty->assign('num_pages', ceil($toplevel_reply_count/$page_limit));
$smarty->assign('page_num', $page_num);
$smarty->assign('topic_id', $topic_id);
$smarty->assign(array('company_promoted_replies' => $company_promoted,
                      'star_promoted_replies' => $star_promoted));

$smarty->assign('current_url', 'topic.php?id=' . $topic_id);

$sprink->add_std_hash_elems($smarty);

$smarty->display('topic.t');
?>
