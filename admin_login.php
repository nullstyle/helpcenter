<?
require("class.myatomparser.php");
require("setup.php");
require_once("utils.php");

$h = new hkit;

# TBD: pull this into utils.php
$company_url = api_url('companies/'.$company_id);
if ($quick_mode) {
  $company_hcard = $h->getByString('hcard', file_get_contents($company_url));
} else {
  $company_hcard = $h->getByURL('hcard', $company_url);
}

# dump($company_hcard);
$company_name = $company_hcard[0]["fn"];

$accts = get_users();

$message = $_GET['wrong_password'] 
    ? 'The username and password you entered did not match. Please try again.'
    : '';

$smarty->assign('company_name', $company_name);
$smarty->assign('accts', $accts);
$smarty->assign('message', $message);
$smarty->display('admin_login.t');

?>
