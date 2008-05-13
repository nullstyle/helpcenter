<?php
try {

require_once("Sprinkles.php");

$h = new hkit;

$sprink = new Sprinkles();

$smarty->assign('body_css_id', 'contactus');
$smarty->assign('current_url', 'contactus.php');

$sprink->add_std_hash_elems($smarty);

$smarty->display('contactus.t');

finish_request('contactus');

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>