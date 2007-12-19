<?
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
# dump($company_hcard);
$company_name = $company_hcard["fn"];

$options = array();

$filter_style = request_param('style');
if ($filter_style) {
  $options['style'] = $filter_style;
}

$filter_product_id = request_param('product');
$filter_product = array();
if ($filter_product_id) {
  $options['product'] = $filter_product_id;
  $filter_product = $sprink->get_product($filter_product_id);
}

$filter_tag = request_param('tag');
if ($filter_tag) $options['tag'] = $filter_tag;

$topics = $sprink->topics($options);
assert(is_array($topics));
$topic_count = count($topics);
$topics = take($discuss_topic_page_limit, $topics); # FIXME needs pagination

foreach ($topics as &$topic) {
  if (!($topic["reply_count"] > 0)) 
    $topic["reply_count"] = 0;
}

$top_topic_tags = array('underwear');

$smarty->assign('background_color', $sprink->site_background_color());
$smarty->assign('top_topic_tags', $top_topic_tags);
$smarty->assign(array('filter_product' => $filter_product,
                      'filter_style' => $filter_style,
                      'filter_tag' => $filter_tag));
$smarty->assign('products', $sprink->products());
$smarty->assign('company_name', $company_name);
$smarty->assign('topics', $topics);
$smarty->assign('topic_count', $topic_count);
$smarty->assign('filter_product_arg',
                       $filter_product ?
                         '&' ."product=" . $filter_product['uri'] :
                         '');
$smarty->assign('filter_tag_arg',
                       $filter_tag ?
                         '&' ."tag=" . $filter_tag :
                         '');
$smarty->display('discuss.t');
?>
