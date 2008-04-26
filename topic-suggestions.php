<?php

require_once('Sprinkles.php');

$query = request_param('query');

$mode = request_param('mode');
if (!$mode) $mode = 'simple';

$sprink = new Sprinkles();
$suggested = $sprink->topics(array('query' => $query));

$topics = take(3, $suggested['topics']);

if ($mode=='fancy')
  $sprink->resolve_authors($topics);

$smarty->assign('suggested_topics', $topics);
$smarty->assign('mode', $mode);

$smarty->display('topic-suggestions.t');

finish_request('topic-suggestions');

?>