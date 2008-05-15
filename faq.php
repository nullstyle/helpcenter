<?php
try {

require_once("Sprinkles.php");

$sprink = new Sprinkles();

$faqs = $sprink->topics(array('frequently_asked' => 1,
                              'style' => 'question'));

$smarty->assign('entries', $entries);
$smarty->assign('faqs', $faqs['topics']);

$smarty->assign('current_url', 'faq.php');

$sprink->add_std_hash_elems($smarty);

$smarty->display('faq.t');

finish_request('faq');

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>
