<?php
try {
require_once("Sprinkles.php");

$sprink = new Sprinkles();

$message = $_GET['wrong_password'] 
    ? 'The username and password you entered did not match. Please try again.'
    : '';

$smarty->assign('site_configured', $sprink->site_configured());
$smarty->assign('message', $message);
$smarty->assign('current_url', 'admin.php');

$sprink->add_std_hash_elems($smarty);

$smarty->display('admin-login.t');

finish_request('admin-login');

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}

?>
