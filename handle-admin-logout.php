<?

require_once('Sprinkles.php');

$sprink = new Sprinkles($company_id);

$sprink->close_admin_session();

redirect('logged-out.php');

?>