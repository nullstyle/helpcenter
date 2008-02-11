<?php

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$sprink->close_admin_session();

redirect('logged-out.php');

?>
