<?
require("class.myatomparser.php");
require("setup.php");
require_once("utils.php");

$h = new hkit;

###### GET COMPANY INFO ######

## TBD: pull this into utils.php
$company_url = api_url('companies/' . $company_id);
if ($quick_mode) {
  $company_hcard = $h->getByString('hcard', file_get_contents($company_url));
} else {
  $company_hcard = $h->getByURL('hcard',$company_url);
}

$topics = array();

$smarty->assign('company_name', $company_name);
$smarty->assign('topics', $topics);
$smarty->display('minidashboard.t');
?>
