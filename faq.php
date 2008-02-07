<?php
require_once("config.php");
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$faqs = $sprink->topics(array('frequently_asked' => 1,
                              'style' => 'question'));

$smarty->assign('entries', $entries);
$smarty->assign('faqs', $faqs['topics']);

$smarty->assign('current_url', 'faq.php');

$sprink->add_std_hash_elems($smarty);

$smarty->display('faq.t');

?>
