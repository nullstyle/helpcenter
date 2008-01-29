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

$admin_users = $sprink->get_users(); # TBD: rename this to ->admins()
$smarty->assign('admin_users', $admin_users);

foreach ($fields as $i => $field) {
  if (request_param($field)) {
    $settings[$field] = request_param($field);
  }
}

if (request_param('admin_users'))
  $settings['admin_users_str'] = request_param('admin_users');

$company_hcard = $sprink->company_hcard();
$smarty->assign('company_url', $company_hcard['url']);

$smarty->assign('invalid', request_param('invalid'));
$smarty->assign('errors', request_param('errors'));
$smarty->assign('hooked_msg', request_param('hooked'));
$smarty->assign('admins_changed', request_param('admins_changed'));
$smarty->assign('settings', $settings);
$smarty->assign('current_url', 'admin.php');   # FIXME: this leads to odd behavior on 
                                               # logout: user goes straight to sign-in
                                               # page; consider taking the logout link
                                               # to some other page.

$sprink->add_std_hash_elems($smarty);

$smarty->display('admin.t');

?>