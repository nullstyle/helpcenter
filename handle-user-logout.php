<?php

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$sprink->close_session();

$return = request_param('return');
if (!$return) $return = 'helpstart.php';

redirect($return);

?>
