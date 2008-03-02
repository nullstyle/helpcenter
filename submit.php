<?php
require_once('Sprinkles.php');

$sprink = new Sprinkles();

$subject = request_param('subject');
$suggested = $sprink->topics(array('query' => $subject,
                                   'notags' => true));

$suggested = take($submit_suggestions, $suggested['topics']);

$sprink->resolve_authors($suggested);

$smarty->assign('subject', $subject);
$smarty->assign('suggested', $suggested);
$products = $sprink->products();
foreach ($products as &$product) {
  $matches = array();
  preg_match('!/(\d+)!', $product['uri'], &$matches);
  $product['sfn_id'] = $matches[1];
}
$smarty->assign('products', $products);

$smarty->assign('current_url', 'submit.php');

$sprink->add_std_hash_elems($smarty);

$smarty->display('submit.t');

?>