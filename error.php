<?php
require_once('Sprinkles.php');

$smarty->assign('sprinkles_root_url', sprinkles_root_url());

try {
  $sprink = new Sprinkles();
  $sprink->add_std_hash_elems($smarty);
} catch (Exception $e) {
  $smarty->assign('background_color', '#86fff6');
}

$smarty->display('error.t');
?>
