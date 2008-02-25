<?php

require_once('Sprinkles.php');

$sprink = new Sprinkles();

$smarty->assign('complainant_name', request_param('name'));

$sprink->add_std_hash_elems($smarty);

$smarty->display('contactus-success.t');

?>