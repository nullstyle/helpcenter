<?

require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_name = $sprink->company_name();

$accts = $sprink->get_users();
foreach ($accts as &$acct) {
  $acct = $acct['username'];
}
#$accts = $sprink->employees();
#foreach ($accts as &$acct) {
#  $acct = $acct['nickname'];
#}

$message = $_GET['wrong_password'] 
    ? 'The username and password you entered did not match. Please try again.'
    : '';

$smarty->assign('site_configured', $sprink->site_configured());

$smarty->assign('background_color', $sprink->site_background_color());
$smarty->assign('company_name', $company_name);
$smarty->assign('current_user', $sprink->current_user());
$smarty->assign('accts', $accts);
$smarty->assign('message', $message);

$smarty->display('admin_login.t');

?>
