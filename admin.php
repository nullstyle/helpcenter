<?
require_once("setup.php");
require_once("utils.php");
require_once('admin-page.php');

$username = current_user();
if (!$username)
  header('Location: admin_login.php', true, 302);

require("class.myatomparser.php");

$h = new hkit;

# TBD: pull this into utils.php
#$company_url = $quick_mode ?
#  $cache_dir.'companies-' . $company_id : 
#  $api_root.'/companies/'.$company_id;
$company_url = api_url('companies/'.$company_id);
# print $company_url;
if ($quick_mode) {
  $company_hcard = $h->getByString('hcard', file_get_contents($company_url));
} else {
  $company_hcard = $h->getByURL('hcard',$company_url);
}

# dump($company_hcard);
$company_name = $company_hcard[0]["fn"];

$topics_feed_url = api_url('/companies/' . $company_id . '/topics');
$atom = new myAtomParser($topics_feed_url);
# dump($atom->output);
foreach ($atom->output as $feed) {
  $entries = $feed[""]["ENTRY"];        # FIXME extra level here.
  $entries = take($helpstart_topic_count, $entries);
}

$company_people_url = api_url('companies/'.$company_id.'/people');
if ($quick_mode) {
  $company_people_list = $h->getByString('hcard',
                                         file_get_contents($company_people_url));
} else {
  $company_people_list = $h->getByURL('hcard',$company_people_url);
}

$sql = "select background_color, logo_url, contact_email, contact_phone, " . 
       "contact_address, map_url, faq_type from site_settings";
$result = mysql_query($sql);

$settings = mysql_fetch_assoc($result);

foreach ($fields as $i => $field) {
  if (request_param($field)) {
    $settings[$field] = request_param($field);
  }
}

$smarty->assign('invalid', $_GET['invalid']);
$smarty->assign('errors', $_GET['errors']);
$smarty->assign('settings', $settings);
$smarty->assign('username', $username);
$smarty->assign('company_name', $company_name);
$smarty->display('admin.t');

?>
