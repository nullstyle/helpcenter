<?php

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$sprink->close_session();

redirect(request_param('return'));

?>
