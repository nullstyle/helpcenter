<?
require_once("config.php");
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$faqs = $sprink->topics(array('frequently_asked' => 1,
                              'style' => 'question'));

$smarty->assign('entries', $entries);
$smarty->assign('faqs', $faqs['topics']);

# Standard stash items
$smarty->assign('background_color', $sprink->site_background_color());
$smarty->assign('company_name', $company_name);

$smarty->assign('current_url', 'faq.php');
$smarty->assign('user_name', $sprink->current_username());
$smarty->assign('current_user', $sprink->current_user());

$smarty->display('faq.t');

?>
