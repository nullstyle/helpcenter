<?
require_once("config.php");
require_once("Sprinkles.php");
require_once('admin-fields.php');

$sprink = new Sprinkles();

$username = $sprink->current_username();
if (!$username)
  redirect('admin_login.php');

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$sql = "select background_color, contact_email, contact_phone, " . 
       "contact_address, map_url, faq_type from site_settings";
$result = mysql_query($sql);

$settings = mysql_fetch_assoc($result);

foreach ($fields as $i => $field) {
  if (request_param($field)) {
    $settings[$field] = request_param($field);
  }
}

$smarty->assign('background_color', $sprink->site_background_color());

$smarty->assign('site_dirty', true);       # FIXME: make it false at first setup
$smarty->assign('invalid', $_GET['invalid']);
$smarty->assign('errors', $_GET['errors']);
$smarty->assign('settings', $settings);
$smarty->assign('current_user', $sprink->current_user());
$smarty->assign('user_name', $username);
$smarty->assign('company_name', $company_name);
$smarty->assign('current_url', 'admin.php');

$smarty->display('admin.t');

?>
