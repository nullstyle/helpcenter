<?
require_once('Sprinkles.php');

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$user = $sprink->current_user();

# die($user['canonical_name']);

$all_topics = $sprink->dashboard_topics($user['canonical_name']);

assert($all_topics);
assert(count($all_topics) > 0);

list($company_topics, $noncompany_topics) = 
    $sprink->company_partition($all_topics);

$company_topics = take(5, $company_topics);
$noncompany_topics = take(4, $noncompany_topics);

$sprink->resolve_companies($noncompany_topics);

$smarty->assign('company_topics', $company_topics);
$smarty->assign('noncompany_topics', $noncompany_topics);

$smarty->assign('current_url', 'minidashboard.php');
$smarty->assign('entries', $entries['topics']);

$sprink->add_std_hash_elems($smarty);

$smarty->display('minidashboard.t');

?>
