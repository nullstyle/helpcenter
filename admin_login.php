<?
require_once("Sprinkles.php");

$sprink = new Sprinkles($company_id);

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$accts = $sprink->get_users();

$message = $_GET['wrong_password'] 
    ? 'The username and password you entered did not match. Please try again.'
    : '';

$smarty->assign('company_name', $company_name);
$smarty->assign('accts', $accts);
$smarty->assign('message', $message);
$smarty->display('admin_login.t');

?>
