<?

require_once('Sprinkles.php');

$sprink = new Sprinkles($company_id);

$sprink->close_session();

redirect('dead-end.php');

?>