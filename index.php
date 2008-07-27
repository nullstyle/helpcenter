<?php

require_once('Sprinkles.php');

$sprink = new Sprinkles();

if ($sprink->site_configured()) {
  redirect('helpstart.php');
} else {
  redirect('admin-findsite.php');
}
exit(0);

?>
