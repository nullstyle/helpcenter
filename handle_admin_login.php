<?

require_once('Sprinkles.php');
$sprink = new Sprinkles($company_id);

$username = $_GET['username'];
$password = $_GET['password'];

$q = "select password from users where username = '" . $username . "'";
$result = mysql_query($q);
# assert($result);
# if (!$result) return;
$cols = mysql_fetch_assoc($result);
# dump($cols);
if ($cols['password'] == $password) {
  # FIXME encryption!
  $sprink->open_session($username);
  header('Location: admin.php', true, 302);
#  http_redirect('admin_login.php');
} else {
  header('Location: admin_login.php?wrong_password=true', true, 302);
#  http_redirect('admin_login.php?wrong_password=true');
}

?>

