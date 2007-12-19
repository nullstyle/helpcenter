<?

header('Content-type: image/png');

require_once('Sprinkles.php');

$sprink = new Sprinkles($company_id);

print $sprink->site_logo();

?>