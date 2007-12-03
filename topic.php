<? 
require_once("setup.php");
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$topic_id = request_param('id');
$items = $sprink->topic($topic_id);

$lead_item = array_shift($items);

$page_limit = 5;

$page_num = request_param('page');
if (!$page_num) { $page_num = 0; }

$reply_count = count($items);

$items = take_range($page_num * $page_limit, ($page_num + 1) * $page_limit,
                    $items);

$smarty->assign(array('topic_updated' => $lead_item['updated'],
                      'topic_updated_relative' =>
                           ago($lead_item['updated'], time())));

$smarty->assign('company_name', $company_name);
$smarty->assign('test', array('foo' => array('baz' => 'bonk')));
$smarty->assign('items', $items);
$smarty->assign('lead_item', $lead_item);
$smarty->assign('replies', $items);
$smarty->assign('reply_count', $reply_count);
$smarty->assign('num_pages', ceil($reply_count/$page_limit));
$smarty->assign('page_num', $page_num);
$smarty->assign('topic_id', $topic_id);

$smarty->display('topic.t');
?>
