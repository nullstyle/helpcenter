<?php

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$site = request_param('site');
# TBD: validate these fields.
$oauth_consumer_key = request_param('oauth_consumer_key');
$oauth_consumer_secret = request_param('oauth_consumer_secret');
$sprinkles_root_url = request_param('sprinkles_root_url');
$sprinkles_root_url = preg_replace('|[^/]*.php$|', '', $sprinkles_root_url);
# Note: the naive regex you'd usee below doesn't work; pcre is not in fact
# Perl-compatible in this case
$sprinkles_root_url = preg_replace('|([^/])/*$|', '\1/', $sprinkles_root_url);

$result = $sprink->set_site_settings(
                array('company_id' => $site,
                'oauth_consumer_key' => $oauth_consumer_key,
                'oauth_consumer_secret' => $oauth_consumer_secret,
                'sprinkles_root_url' => $sprinkles_root_url));
if (!$result) die (mysql_error());

redirect('handle-user-login.php?first_login=true&return=admin.php?hooked=true');

?>
