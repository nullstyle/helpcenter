<?php
require_once('Sprinkles.php');

$smarty->assign('sprinkles_root_url', sprinkles_root_url());

$smarty->display('dead-end.t');
?>
