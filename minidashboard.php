<?
require_once('Sprinkles.php');

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$all_topics = $sprink->dashboard_topics('scott');  # FIXME scott is not the only person

assert($all_topics);
assert(count($all_topics) > 0);

$company_topics = $sprink->company_filter($all_topics);

$company_topics = take(5, $company_topics);
$all_topics = take(4, $all_topics);

$sprink->resolve_companies($all_topics);

$smarty->assign('company_topics', $company_topics);
$smarty->assign('all_topics', $all_topics);

# Standard stash items
$smarty->assign('background_color', $sprink->site_background_color());
$smarty->assign('company_name', $company_name);
$smarty->assign('current_url', 'minidashboard.php');
$smarty->assign('entries', $entries['topics']);
$smarty->assign('username', $sprink->current_username());

$smarty->display('minidashboard.t');

?>
