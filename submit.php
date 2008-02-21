<?php
require_once('Sprinkles.php');

$sprink = new Sprinkles();

$subject = request_param('subject');
$suggested = $sprink->topics(array('query' => $subject,
                                   'notags' => true));

$suggested = take($submit_suggestions, $suggested['topics']);

$smarty->assign('subject', $subject);
$smarty->assign('suggested', $suggested);

$smarty->assign('current_url', 'submit.php');

$sprink->add_std_hash_elems($smarty);

$smarty->display('submit.t');

?>
