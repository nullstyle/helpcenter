<?php

require_once('Sprinkles.php');

$sprink = new Sprinkles();

if ($sprink->site_configured()) {
  redirect('helpstart.php');
} else {
  redirect('admin_findsite.php');
}

?>
