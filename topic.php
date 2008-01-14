<? 
require_once("setup.php");
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
$lead_item = array_shift($topic['items']);
$reply_count = count($topic['items']);
$topic['items'] = $sprink->thread_items($topic['items'], $lead_item['id']);
$toplevel_reply_count = count($topic['items']);
$topic['items'] = take_range($page_num * $page_limit, ($page_num + 1) * $page_limit,
                    $topic['items']);
$topic['items'] = $sprink->flatten_threads($topic['items']);

$user = $sprink->current_user();

$smarty->assign(array('topic_updated' => $lead_item['updated'],
                      'topic_updated_relative' =>
                           ago($lead_item['updated'], time())));

# $smarty->assign(array('topic_published' => $lead_item['published'],
#                      'topic_published_relative' =>
#                           ago($lead_item['published'], time())));

$smarty->assign('background_color', $sprink->site_background_color());
$smarty->assign('company_name', $company_name);

$smarty->assign('lead_item', $lead_item);
$smarty->assign('replies', $topic['items']);
$smarty->assign('particip', $topic['particip']);
$smarty->assign('tags', $topic['tags']);
$smarty->assign(array('reply_count' => $reply_count,
                      'toplevel_reply_count' => $toplevel_reply_count));
$smarty->assign('num_pages', ceil($toplevel_reply_count/$page_limit));
$smarty->assign('page_num', $page_num);
$smarty->assign('topic_id', $topic_id);
$smarty->assign('username', $user ? 'TBD' : '');
$smarty->assign('current_url', 'topic.php?id=' . $topic_id);

$smarty->display('topic.t');
?>
