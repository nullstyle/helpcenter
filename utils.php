<?php
#require_once 'XML/Feed/Parser.php';
require_once 'setup.php';

function take($n, $list) {
  $result = array();
  $i = 0;
  foreach ($list as $item) {
    if ($i++ > $n) { break; }
    array_push($result, $item);
  }
  return $result;
}

function ago($time, $now) {
  $diff = $now - $time;
  if ($diff < 60) {
    return $diff . " seconds ago";
  } else if ($diff < 3600) {
    return (ceil($diff/60)) . " minutes ago";
  } else if ($diff < 24 * 3600) {
    return (ceil($diff/3600)) . " hours ago";
  } else if ($diff < 7 * 24 * 3600) {
    return (ceil($diff/(24*3600))) . " days ago";
  } else if ($diff < 365 * 3600) {
    return (ceil($diff/(30*24*3600))) . " months ago";
  } else {
    return (ceil($diff/(365.24*24*3600))) . " years ago";
  }
}

function dump($obj) {
  print("<pre>");
  var_dump($obj);
  print("</pre>");
}
require_once('hkit.class.php');

$h = new hKit;

function get_person($url) {
  $person = array();
  if ($quick_mode) {
    $person = $h->getByString('hcard',
                              file_get_contents($cache_dir."people-40451.html"));
  } else {
    $person = $h->getByURL('hcard', $url);
  }
  return $person;
}

function api_url($path) {
  if ($quick_mode) {
    preg_replace($path, "/", "-"); 
  }
  print $path;
  return $quick_mode ?
    $cache_dir . $path :
    $api_root . $path;
}

?>
