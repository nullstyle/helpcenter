<?
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

## Validation

# FIXME: not finished

$ok = true;
$bad_fields = array();

$background_color = request_param('background_color');

# print "background color: $background_color ";

if (preg_match($hexcolor_regex, $background_color)) {
  $background_color = trim($background_color);
} else {
  array_push($bad_fields, 'background_color');
  $ok = false;
}

$contact_email = request_param('contact_email');

if (preg_match($email_regex, $contact_email)) {
  $contact_email = trim($contact_email);
} else {
  array_push($bad_fields, 'contact_email');
  $ok = false;
}

$contact_phone = request_param('contact_phone');

$contact_address = request_param('contact_address');

$map_url = request_param('map_url');

if ($_FILES['logo']['tmp_name']) {
  $logo_data = file_get_contents($_FILES['logo']['tmp_name']);
  file_put_contents('logo.png', $logo_data);
}

# TBD: additional links

$sprink = new Sprinkles();  # TBD: this is expensive; cheapen!

$existing_admin_users = $sprink->get_users();
sort($existing_admin_users);
$admin_users_str = request_param('admin_users_str');
$admin_users = preg_split('/,\s*|\s+/', $admin_users_str);
$admin_users = array_filter($admin_users);
sort($admin_users);
# TBD: refactor this routine, "contains"
$admins_changed = false;
foreach ($admin_users as $u) {
  if ($existing_admin_users[0]['username'] != $u) { $admins_changed = true; break; }
  else { array_shift($existing_admin_users); }
}

if ($ok) {
  ## Save the settings
  # FIXME: not finished
  # FIXME: needs encoding.
  $sql = 'update site_settings set ' . 
         'background_color = \'' . mysql_real_escape_string($background_color). '\', ' .
         'contact_email = \'' . mysql_real_escape_string($contact_email). '\', ' .
         'contact_address = \'' . mysql_real_escape_string($contact_address). '\', ' .
         'map_url = \'' . mysql_real_escape_string($map_url). '\', ' .
         'contact_phone = \'' . mysql_real_escape_string($contact_phone).'\', ' .
         'configured = \'Y\' ' .
         ($logo_data ?
           ', logo_data = \'' . mysql_real_escape_string($logo_data) . '\'' : '')
 ;
  # print $sql;
  $result = mysql_query($sql);
  if (!$result) { print mysql_error(); return; }
  $sprink->set_admin_users($admin_users);
  $flags = '';
  $flags .= ($admins_changed) ? '&admins_changed=true' : '';
  redirect('admin.php?settings_saved=true' . $flags);
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