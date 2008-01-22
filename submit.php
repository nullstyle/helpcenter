<?
require_once('Sprinkles.php');

$sprink = new Sprinkles($company_id);

$subject = request_param('subject');
$suggested = $sprink->topics(array('query' => $subject,
                                   'notags' => true));

#$suggested = take($submit_suggestions, $suggested['topics']);
$suggested = take(3, $suggested['topics']);

$smarty->assign('subject', $subject);
$smarty->assign('suggested', $suggested);

# Standard stash items
$smarty->assign('background_color', $sprink->site_background_color());
$smarty->assign('company_name', $sprink->company_name());
$smarty->assign('user_name', $sprink->current_username());
$smarty->assign('current_user', $sprink->current_user());
$smarty->assign('current_url', 'submit.php');

$smarty->display('submit.t');

?>
