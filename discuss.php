<?php
require_once("Sprinkles.php");

$sprink = new Sprinkles();

$page_num = request_param('page');
if (!$page_num) { $page_num = 0; }

$topic_filters = array();

$filter_style = request_param('style');
if ($filter_style) {
  $topic_filters['style'] = $filter_style;
}

$filter_style_arg = $filter_style ? '&style=' . $filter_style : '';

$filter_product_id = request_param('product');

if ($filter_product_id) {
  $topic_filters['product'] = $filter_product_id;
  $filter_product = $sprink->get_product($filter_product_id);
}

$filter_product_arg = $filter_product ?
                         '&product=' . $filter_product['uri'] :
                         '';

$filter_tag = request_param('tag');
if ($filter_tag) $topic_filters['tag'] = $filter_tag;

$filter_tag_arg = $filter_tag ? '&tag=' . $filter_tag : '';

$topics = $sprink->topics($topic_filters, ($page_num + 1) * $discuss_page_size);
$topic_count = $topics['totals']['this'];
$topics['topics'] = take_range($page_num * $discuss_page_size,
                               ($page_num + 1) * $discuss_page_size,
                               $topics['topics']);
$sprink->resolve_authors($topics['topics']);

$smarty->assign('page_num', $page_num);
$smarty->assign('num_pages', ceil($topic_count/$topic_page_size));
$smarty->assign('sfn_root', $sfn_root);

$top_topic_tags = take($max_top_topic_tags, 
                       $sprink->tags($api_root . 'companies/' . 
                                     $sprink->company_sfnid . 
                                     '/tags?on=topics&sort=usage&limit=5'));
# Sets the label for topic-box
switch ($filter_style) {
case 'question':
  $friendly_style = 'question';
  $topic_list_template = "question.t";
  $style_label = 'What do you want to ask?';
  break;
case 'idea':
  $friendly_style = 'idea';
  $topic_list_template = "idea.t";
  $style_label = 'What do you want to share?';
  break;
case 'problem':
  $friendly_style = 'problem';
  $topic_list_template = "problem.t";
  $style_label = 'What do you want to report';
  break;
case 'talk':
  $friendly_style = 'discussion';
  $topic_list_template = "talk.t";
  $style_label = 'What do you want to discuss?';
  break;
default:
  $friendly_style = 'topic';
  $topic_list_template = "mixed-topic-list.t";
}
$smarty->assign('friendly_style', $friendly_style);
$smarty->assign('style_label', $style_label);
$smarty->assign('topic_list_template', $topic_list_template);

# discuss_tag_url: function to allow template designers to get the URL of a 
# discussion page for a particular tag.
function discuss_tag_url($params, &$smarty) {
  return('discuss.php?tag=' . $params['tag']);
}
$smarty->register_function('discuss_tag_url', 'discuss_tag_url');

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

$smarty->assign('current_url', 'discuss.php?' . $filter_style_arg);

$sprink->add_std_hash_elems($smarty);

$smarty->display('discuss.t');

finish_request('discuss');
?>