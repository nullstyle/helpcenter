<?
require_once("class.myatomparser.php");
require_once("setup.php");
require_once("utils.php");

$h = new hkit;

# TBD: pull this into utils.php
$company_url = api_url("companies/$company_id");
if ($quick_mode) {
  $company_hcard = $h->getByString('hcard', file_get_contents($company_url));
} else {
  $company_hcard = $h->getByURL('hcard',$company_url);
}

# dump($company_hcard);
$company_name = $company_hcard[0]["fn"];

$topics_feed_url = api_url("companies/$company_id/topics");
$atom = new myAtomParser($topics_feed_url);
# dump($atom->output);
foreach ($atom->output as $feed) {
  $entries = $feed[""]["ENTRY"];        # FIXME extra level here.
  $entries = take($helpstart_topic_count, $entries);
}

$company_people_url = api_url("companies/$company_id/people");

if ($quick_mode) {
  $company_people_list = $h->getByString('hcard',
                                         file_get_contents($company_people_url));
} else {
  $company_people_list = $h->getByURL('hcard', $company_people_url);
}
# dump($company_people_list);
$company_people = array();
foreach ($company_people_list as $person) {
  $person_record = $h->getByURL('hcard', $person["url"]);
#   $h->getByString('hcard',
#                   file_get_contents($cache_dir."people-40451.html"));
  array_push($company_people, $person_record[0]);
}
# print "\$company_people:"; dump($company_people);
assert(is_array($company_people));

$smarty->assign('company_people', $company_people);
$smarty->assign('company_name', $company_name);
$smarty->assign('entries', $entries);

$smarty->assign('now', mysql_now());

$smarty->display('helpstart.t');

?>
