<?php
try {

require_once('Sprinkles.php');

$sprink = new Sprinkles();


$company_sfnid = request_param('company_sfnid');
$oauth_consumer_key = request_param('oauth_consumer_key');
$oauth_consumer_secret = request_param('oauth_consumer_secret');
$sprinkles_root_url = request_param('sprinkles_root_url');


if (!$oauth_consumer_key || !$oauth_consumer_secret) {
  redirect('admin-findsite.php?msg=missing_oauth' .
                             '&company_sfnid=' . $company_sfnid . 
                             '&oauth_consumer_key=' . $oauth_consumer_key . 
                             '&oauth_consumer_secret=' . $oauth_consumer_secret . 
                             '&sprinkles_root_url=' . $sprinkles_root_url);
  exit();
}

if (!$sprinkles_root_url) {
  redirect('admin-findsite.php?msg=missing_sprinkles_root_url' .
                             '&company_sfnid=' . $company_sfnid . 
                             '&oauth_consumer_key=' . $oauth_consumer_key . 
                             '&oauth_consumer_secret=' . $oauth_consumer_secret . 
                             '&sprinkles_root_url=' . $sprinkles_root_url);
  exit();
}

if (!$company_sfnid) {
  redirect('admin-findsite.php?msg=missing_company_sfnid' .
                             '&company_sfnid=' . $company_sfnid . 
                             '&oauth_consumer_key=' . $oauth_consumer_key . 
                             '&oauth_consumer_secret=' . $oauth_consumer_secret . 
                             '&sprinkles_root_url=' . $sprinkles_root_url);
  exit();
}

$sprinkles_root_url = preg_replace('|[^/]*.php$|', '', $sprinkles_root_url);
# Note: the naive regex you'd usee below doesn't work; pcre is not in fact
# Perl-compatible in this case
$sprinkles_root_url = preg_replace('|([^/])/*$|', '\1/', $sprinkles_root_url);

$result = $sprink->set_site_settings(
                array('company_id' => $company_sfnid,
                      'oauth_consumer_key' => $oauth_consumer_key,
                      'oauth_consumer_secret' => $oauth_consumer_secret,
                      'sprinkles_root_url' => $sprinkles_root_url));
if (!$result) die (mysql_error());

message($sprink->site_configured());
redirect($sprink->authorize_url('admin.php?hooked=true', true));
  exit(0);

} catch (Exception $e) {
  error_log("Exception thrown while preparing page: " . $e->getMessage());
  $smarty->display('error.t');
}
?>
