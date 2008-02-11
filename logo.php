<?php

header('Content-type: image/png');

require_once('Sprinkles.php');

$sprink = new Sprinkles();

print $sprink->site_logo();

?>
