<?php
require_once("config.php");
require_once("Sprinkles.php");
require_once('admin-fields.php');

$sprink = new Sprinkles();

$user = $sprink->current_user();

$username = $sprink->current_username();
if (!$username) {
  redirect('admin-login.php');
  exit(0);
}
$admin_users = $sprink->get_users();
if (!$sprink->user_is_admin()) {
  redirect('dead-end.php');   # FIXME: find a more gracious out for user
  exit(0);
}

$company_hcard = $sprink->company_hcard();
$company_name = $company_hcard["fn"];

$sql = "select background_color, contact_email, contact_phone, " . 
       "contact_address, logo_link, map_url, faq_type from site_settings";
$result = mysql_query($sql);

$settings = mysql_fetch_assoc($result);

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

$sprinkles_tagged_topics = $sprink->topics(array('tag' => 'sprinkles'));
$sprinkles_tagged_topics = take(3, $sprinkles_tagged_topics['topics']);

$site_links = $sprink->site_links();
$smarty->assign('site_links', $site_links);

$smarty->assign('invalid', request_param('invalid'));
$smarty->assign('errors', request_param('errors'));
$smarty->assign('settings_saved', request_param('settings_saved'));
$smarty->assign('hooked_msg', request_param('hooked'));
$new_admins = request_param('new_admins');
if ($new_admins)
  $smarty->assign('new_admins', split(',', $new_admins));
$smarty->assign('settings', $settings);
$smarty->assign('sprinkles_root_url', sprinkles_root_url());
$smarty->assign('sprinkles_tagged_topics', $sprinkles_tagged_topics);
$smarty->assign('current_url', 'admin.php');   # FIXME: this leads to odd behavior on 
                                               # logout: user goes straight to sign-in
                                               # page; consider taking the logout link
                                               # to some other page.

$sprink->add_std_hash_elems($smarty);

$smarty->display('admin.t');

?>