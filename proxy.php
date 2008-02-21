<?php

require_once('Sprinkles.php');

$query = request_param('query');

$sprink = new Sprinkles();
$suggested = $sprink->topics(array('query' => $query,
                                   'notags' => true));

$smarty->assign('suggested_topics', take(3, $suggested['topics']));

$smarty->display('topic-suggestions.t');
?>