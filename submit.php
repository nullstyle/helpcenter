<?php
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

$suggested = $sprink->topics(array('query' => $subject,
                                   'notags' => true));

$suggested = take($submit_suggestions, $suggested['topics']);

$sprink->resolve_authors($suggested);

$smarty->assign('subject', $subject);
$smarty->assign('details', $details);
$smarty->assign('tags', $tags);
$smarty->assign('emoticon', $face);
$smarty->assign('emotion', $emotion);
$smarty->assign('style', $style);
$smarty->assign('product', $selected_products);

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

?>