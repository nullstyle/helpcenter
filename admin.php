<?
require("class.myatomparser.php");
require("setup.php");
require_once("utils.php");

$h = new hkit;

# TBD: pull this into utils.php
$company_url = $quick_mode ?
  $cache_dir.'companies-' . $company_id : 
  $api_root.'/companies/'.$companyid;
# print $company_url;
if ($quick_mode) {
  $company_hcard = $h->getByString('hcard', file_get_contents($company_url));
} else {
  $company_hcard = $h->getByURL('hcard',$company_url);
}

# dump($company_hcard);
$company_name = $company_hcard[0]["fn"];

if ($quick_mode) {
  $topics_feed_url = $cache_dir . 'companies-' . $company_id . '-topics.atom';
} else {
  $topics_feed_url = $api_root . "/companies/'.$company_id.'/topics";
}
$atom = new myAtomParser($topics_feed_url);
# dump($atom->output);
foreach ($atom->output as $feed) {
  $entries = $feed[""]["ENTRY"];        # FIXME extra level here.
  $entries = take($helpstart_topic_count, $entries);
}

$company_people_url = $quick_mode ? 
    $cache_dir.'companies-' . $company_id . '-people' : 
    $api_root.'/companies/'.$company_id.'/people';
# print($company_people_url);

if ($quick_mode) {
  $company_people_list = $h->getByString('hcard',
                                         file_get_contents($company_people_url));
} else {
  $company_people_list = $h->getByURL('hcard',$company_people_url);
}

$smarty->assign('company_name', $company_name);
$smarty->display('admin.t');

?>
