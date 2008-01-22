<?

require_once('Sprinkles.php');

$site = request_param('site');

$sprink = new Sprinkles();
$result = $sprink->set_company($site);
if (!$result) die (mysql_error());

redirect('handle-user-login.php?return=admin_setup.php');

?>
