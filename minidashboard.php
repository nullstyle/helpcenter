<?php
require_once('Sprinkles.php');

$sprink = new Sprinkles();

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$username = request_param('username');
if ($username) {
  $user_possessive = $username . "'s";  # FIXME: should use 'fn'
  $is_self = false;
} else if ($user_url = request_param('user_url')) {
  $user = $sprink->get_person($user_url);
  $user_possessive = $user['fn'] . "'s";
  $username = $user['canonical_name'];
  $is_self = false;
} else {
  $user = $sprink->current_user();
  $user_possessive = 'your';
  $username = $user['canonical_name'];
  $is_self = true;
}

# die($user['canonical_name']);

$all_topics = $sprink->dashboard_topics($username);

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
$smarty->assign('user_possessive', $user_possessive);
$smarty->assign('user_is_self', $is_self);

$sprink->add_std_hash_elems($smarty);

$smarty->display('minidashboard.t');

?>