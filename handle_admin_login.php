<?

require_once('Sprinkles.php');
$sprink = new Sprinkles();

$username = $_GET['username'];
$password = $_GET['password'];

$q = "select password from users where username = '" . $username . "'";
$result = mysql_query($q);
if ($result) {
  $cols = mysql_fetch_assoc($result);
  if ($cols['password'] == $password) {
    # FIXME encryption!
    $sprink->open_session($username);
    #header('Location: admin.php', true, 302);
    redirect('admin.php');
  }
#header('Location: admin_login.php?wrong_password=true', true, 302);
redirect('admin_login.php?wrong_password=true');

?>

