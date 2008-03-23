<?php

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$defaults = $sprink->findsite_data();

$sprinkles_root_url = request_param('sprinkles_root_url');
if (!$sprinkles_root_url)
  $sprinkles_root_url = $defaults[sprinkles_root_url];
if (!$sprinkles_root_url)
  $sprinkles_root_url = getenv('SCRIPT_URI');
$smarty->assign('sprinkles_root_url', $sprinkles_root_url);

$oauth_consumer_key = request_param('oauth_consumer_key');
if (!$oauth_consumer_key)
  $oauth_consumer_key = $defaults['oauth_consumer_key'];
$oauth_consumer_secret = request_param('oauth_consumer_secret');
if (!$oauth_consumer_secret)
  $oauth_consumer_secret = $defaults['oauth_consumer_secret'];

$company_sfnid = request_param('company_sfnid');
if (!$company_sfnid)
  $company_sfnid = $defaults['company_sfnid'];

$smarty->assign(array('oauth_consumer_key' => $oauth_consumer_key,
                      'oauth_consumer_secret' => $oauth_consumer_secret,
                      'company_sfnid' => $company_sfnid));

$smarty->assign('msg', request_param('msg'));
$smarty->assign('background_color', '#86fff6');
$smarty->assign('no_admin_link', true);

$smarty->display('admin-findsite.t');

?>