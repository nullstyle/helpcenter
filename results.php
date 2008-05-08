<?php
require_once("Sprinkles.php");

$sprink = new Sprinkles();

$page_num = request_param('page');
if (!$page_num) { $page_num = 0; }

$topic_filters = array();

$filter_style = request_param('style');
if ($filter_style) {
  $topic_filters['style'] = $filter_style;
  $smarty->assign('style', $filter_style);
}

$filter_query = request_param('query');
if ($filter_query) {
  $topic_filters['query'] = $filter_query;
  $smarty->assign('query', $filter_query);
}

$topics = $sprink->topics($topic_filters, ($page_num + 1) * $discuss_page_size);
$topic_count = $topics['totals']['this'];
$topics['topics'] = take_range($page_num * $discuss_page_size,
                               ($page_num + 1) * $discuss_page_size,
                               $topics['topics']);
$sprink->resolve_authors($topics['topics']);

$smarty->assign('page_num', $page_num);
$smarty->assign('num_pages', ceil($topic_count/$discuss_page_size));
$smarty->assign('topics', $topics['topics']);
$smarty->assign('topic_count', $topic_count);
$smarty->assign('totals', $topics['totals']);
$sprink->add_std_hash_elems($smarty);

$smarty->display('results.t');

finish_request('results');

?>
