<?php
require_once('Sprinkles.php');

try {
  $sprink = new Sprinkles();
  $sprink->add_std_hash_elems($smarty);
  $smarty->assign('sprinkles_root_url', $sprink->sprinkles_root_url());
  $smarty->assign('error_msg', request_param('msg'));
} catch (Exception $e) {
  $smarty->assign('background_color', '#86fff6');
}

$smarty->display('error.t');
?>
