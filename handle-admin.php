<?php
require_once('Sprinkles.php');
require_once('admin-fields.php');

$hexcolor_regex = "/^\s*#[0-9a-fA-F]{3}([0-9a-fA-F]{3})?$\s*/";

$nonspecial_regex = '[A-Za-z0-9_~`!%^\'#*&$\/|=+-]';
$nonspecial_or_dot_regex = '[A-Za-z0-9_~`,!%^\'#*&$\/|.=+-]';
$word_regex = "[A-Za-z]($nonspecial_regex+[A-Za-z0-9])?";
# $email_regex = "/[A-Za-z0-9.-]+@[A-Za-z0-9.-]+/";
$email_regex = "/$nonspecial_or_dot_regex*@$word_regex(\.$word_regex)*/";
  # c.f. RFC 821 p. 30. Doesn't handle quoted local-parts, #number domain 
  # segments or dotted-quad domain segments.

## Validation  | FIXME: not finished

$bad_fields = array();

$background_color = request_param('background_color');

if (preg_match($hexcolor_regex, $background_color)) {
  $background_color = trim($background_color);
} else {
  array_push($bad_fields, 'background_color');
}

$contact_email = request_param('contact_email');

if (preg_match($email_regex, $contact_email)) {
  $contact_email = trim($contact_email);
} else {
  array_push($bad_fields, 'contact_email');
}

$contact_phone = request_param('contact_phone');

$contact_address = request_param('contact_address');

$map_url = request_param('map_url');

if ($_FILES['logo']['tmp_name']) {
  $logo_data = file_get_contents($_FILES['logo']['tmp_name']);
}

$logo_link = request_param('logo_link');  #TBD: validate as URL?

# TBD: additional links

$sprink = new Sprinkles();  # TBD: this is expensive; cheapen!

$active_username = $sprink->current_username();

if (!$active_username) {
  redirect('admin-login.php'); exit(0);
}

$existing_admin_users = $sprink->get_users();
if (!$sprink->user_is_admin()) {
  redirect('dead-end.php'); exit(0);
}

sort($existing_admin_users);
$admin_users_str = request_param('admin_users_str');
$admin_users = preg_split('/,\s*|\s+/', $admin_users_str);
if (!member($active_username, $admin_users))
  array_push($bad_fields, 'admin_users_str');
else {
  $admin_users = array_filter($admin_users);
  $existing_admin_usernames = array();
  foreach ($existing_admin_users as $u)
    array_push($existing_admin_usernames, $u['username']);
  $new_admins = array_diff($admin_users, $existing_admin_usernames);
}

# TBD: Validate site links?

if (!$bad_fields) {

  ## Save the settings

  # Save the site links.
  $urls = request_param('link_url');
  $texts = request_param('link_text');
  $links = array();
  foreach ($urls as $url) {
    $text = array_shift($texts);
    if ($url || $text) {
      array_push($links, array('url' => $url, 'text' => $text));
    }
  }
  $sprink->set_site_links($links);

  $sql = 'update site_settings set ' . 
         'background_color = \'' . mysql_real_escape_string($background_color). '\', ' .
         'contact_email = \'' . mysql_real_escape_string($contact_email). '\', ' .
         'contact_address = \'' . mysql_real_escape_string($contact_address). '\', ' .
         'map_url = \'' . mysql_real_escape_string($map_url). '\', ' .
         'contact_phone = \'' . mysql_real_escape_string($contact_phone).'\', ' .
         'configured = \'Y\', ' .
         'logo_link = \'' . mysql_real_escape_string($logo_link) . '\' ' .
         ($logo_data ?
           ', logo_data = \'' . mysql_real_escape_string($logo_data) . '\'' : '')
    ;
  $result = mysql_query($sql);
  if (!$result) { print mysql_error(); return; }
  $sprink->set_admin_users($admin_users);
  $params = '';
  if ($new_admins) {
    $params .= '&new_admins=' . join(',', $new_admins);
  }
  redirect('admin.php?settings_saved=true' . $params);
} else {
  foreach ($bad_fields as $field) {
    $params .= '&invalid[' . $field . ']=true';
  }
  foreach ($fields as $field) {
    $params .= '&' . $field . '=' . urlencode(request_param($field));
  }
  $params .= '&admin_users=' . urlencode($admin_users_str);
  redirect('admin.php?errors=true' . $params);
}
?>
