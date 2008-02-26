<?php
require_once('Sprinkles.php');

$sprink = new Sprinkles();

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$username_canonical = request_param('username');
if ($username_canonical) {
  $user = $sprink->get_person($sprink->api_url("people/$username_canonical"));
  $user_possessive = $user['fn'] . "'s";
  $is_self = false;
} else if ($user_url = request_param('user_url')) {
  $user = $sprink->get_person($user_url);
  $user_possessive = $user['fn'] . "'s";
  $username_canonical = $user['canonical_name'];
  $is_self = false;
} else {
  $user = $sprink->current_user();
  $user_possessive = 'your';
  $username_canonical = $user['canonical_name'];
  $is_self = true;
}

# die($user['canonical_name']);

$all_topics = $sprink->dashboard_topics($username_canonical);

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
$smarty->assign('user', $user);
$smarty->assign('username_canonical', $username_canonical);
$smarty->assign('user_possessive', $user_possessive);
$smarty->assign('user_is_self', $is_self);

$sprink->add_std_hash_elems($smarty);

$smarty->display('minidashboard.t');

?>