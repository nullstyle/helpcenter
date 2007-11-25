<?
require_once('utils.php');
require_once('admin-page.php');

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

if (!preg_match($hexcolor_regex, $background_color)) {
  array_push($bad_fields, 'background_color'); $ok = false;
} else {
  $background_color = trim($background_color);
}

$contact_email = request_param('contact_email');

if (!preg_match($email_regex, $contact_email)) {
  array_push($bad_fields, 'contact_email'); $ok = false;
} else {
  $contact_email = trim($contact_email);
}

$contact_phone = request_param('contact_phone');

if ($ok) {
  ## Save the settings
  # FIXME: not finished
  $sql = 'update site_settings set ' . 
         'background_color = \'' . $background_color. '\', ' .
         'contact_email = \'' . $contact_email. '\', ' .
         'contact_phone = \'' . $contact_phone. '\' ' ;
  $result = mysql_query($sql);
  if (!$result) { print mysql_error(); return; }
  redirect('admin.php?settings_saved=true');
} else {
  foreach ($bad_fields as $field) {
    $params .= '&invalid[' . $field . ']=true';
  }
  foreach ($fields as $field) {
    $params .= '&' . $field . '=' . urlencode(request_param($field));
  }
  redirect('admin.php?errors=true' . $params);
}
?>
