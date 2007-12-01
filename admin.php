<?
require_once("setup.php");
require_once("Sprinkles.php");
require_once('admin-page.php');

$sprink = new Sprinkles($company_id);

$username = $sprink->current_user();
if (!$username)
  header('Location: admin_login.php', true, 302);

require("class.myatomparser.php");

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$entries = $sprink->topics(array());
$entries = take($helpstart_topic_count, $entries);

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
