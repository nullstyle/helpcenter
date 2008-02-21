<?php
require_once("Sprinkles.php");

$sprink = new Sprinkles();

$topic_filters = array();

$filter_style = request_param('style');
if ($filter_style) {
  $topic_filters['style'] = $filter_style;
}

$filter_style_arg = $filter_style ? '&style=' . $filter_style : '';

$filter_product_id = request_param('product');
$filter_product = array();
if ($filter_product_id) {
  $topic_filters['product'] = $filter_product_id;
  $filter_product = $sprink->get_product($filter_product_id);
}

$filter_product_arg = $filter_product ?
                         "&product=" . $filter_product['uri'] :
                         '';

$filter_tag = request_param('tag');
if ($filter_tag) $topic_filters['tag'] = $filter_tag;

$filter_tag_arg = $filter_tag ? '&' ."tag=" . $filter_tag : '';

$topics = $sprink->topics($topic_filters);
$topic_count = count($topics['topics']);
$topics['topics'] = take($discuss_topic_page_limit, $topics['topics']); # FIXME needs pagination

$top_topic_tags = take($max_top_topic_tags, 
                       $sprink->tags('http://api.getsatisfaction.com/companies/' . 
                       $sprink->company_sfnid . 
                       '/tags?on=topics&sort=usage&limit=5'));

function discuss_tag_url($params, &$smarty) {
  return('discuss.php?tag=' . $params['tag']);
}

$smarty->assign('top_topic_tags', $top_topic_tags);
$smarty->assign(array('filter_product' => $filter_product,
                      'filter_style' => $filter_style,
                      'filter_tag' => $filter_tag));
$smarty->assign('products', $sprink->products());
$smarty->assign('topics', $topics['topics']);
$smarty->assign('topic_count', $topic_count);
$smarty->assign('filter_product_arg', $filter_product_arg);
$smarty->assign('filter_tag_arg', $filter_tag_arg);

$smarty->assign('totals', $topics['totals']);

$smarty->register_function('discuss_tag_url', 'discuss_tag_url');
$smarty->assign('current_url', 'discuss.php?' . $filter_tag_arg
                                              . $filter_product_arg
                                              . $filter_style_arg);

$sprink->add_std_hash_elems($smarty);

$smarty->display('discuss.t');
?>
