<?php

require_once('Sprinkles.php');

$smarty->assign('background_color', '#86fff6');

$smarty->assign('no_admin_link', true);

$smarty->display('admin-findsite.t');

?>
