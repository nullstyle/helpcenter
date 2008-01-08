<?
require_once('Sprinkles.php');

$subject = request_param('subject');

$smarty->assign('subject', $subject);

$smarty->display('submit.t');

?>