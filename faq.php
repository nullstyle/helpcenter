<?
require_once("setup.php");
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$faqs = $sprink->topics(array('frequently_asked' => 1,
                              'style' => 'question'));

dump($faqs);

$smarty->assign('entries', $entries);

$smarty->assign('background_color', $sprink->site_background_color());
$smarty->assign('company_name', $company_name);

$smarty->assign('faqs', $faqs['topics']);

$smarty->display('faq.t');

?>
