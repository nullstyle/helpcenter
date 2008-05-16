<?php
try {

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$subject = request_param('subject');
$details = request_param('details');
$tags = request_param('tags');
$face = request_param('emoticon');
$emotion = request_param('emotion');
$style = request_param('style');
$selected_products = request_param('product');
if (!$selected_products)
  $selected_products = array();

$suggested = $sprink->topics(array('query' => $subject, "limit" => $submit_suggestions));
$suggested = $suggested['topics'];

$top_tags = take(8, 
                    $sprink->tags('http://api.getsatisfaction.com/companies/' . 
                    $sprink->company_sfnid . 
                    '/tags?on=topics&sort=usage&limit=8'));


switch ($style) {
case 'question':
  $friendly_style = 'question';
  break;
case 'idea':
  $friendly_style = 'idea';
  break;
case 'problem':
  $friendly_style = 'problem';
  break;
case 'talk':
  $friendly_style = 'discussion';
  break;
default:
  $friendly_style = 'topic';
}


$sprink->resolve_authors($suggested);

$smarty->assign('subject', $subject);
$smarty->assign('details', $details);
$smarty->assign('tags', $tags);
$smarty->assign('emoticon', $face);
$smarty->assign('emotion', $emotion);
$smarty->assign('style', $style);
$smarty->assign('friendly_style', $friendly_style);
$smarty->assign('product', $selected_products);
$smarty->assign('top_tags', $top_tags);
$smarty->assign('top_tags_count', count($top_tags));



$smarty->assign('suggested', $suggested);
$products = $sprink->products();
foreach ($products as &$product) {
  $matches = array();
  preg_match('!/(\d+)!', $product['uri'], &$matches);
  $product['sfn_id'] = $matches[1];
  $product['selected'] = in_array($product['name'], $selected_products);
}
$smarty->assign('products', $products);

$smarty->assign('current_url', 'submit.php');

$sprink->add_std_hash_elems($smarty);

$smarty->display('submit.t');

finish_request('submit');

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>