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

function request_param($name) {
  if ($_GET[$name]) {
    return $_GET[$name];
  } else if ($_POST[$name]) {
    return $_POST[$name];
  }
  return;
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
#  if ($quick_mode) {
#    preg_replace($path, "/", "-"); 
#  }
  global $cache_dir;
  global $api_root;
  global $quick_mode;
  return ($quick_mode ?
    ($cache_dir . $path . ".cache") :
    ($api_root . $path));
}

function mysql_now() {
  # for testing DB connection
  mysql_connect();
  mysql_select_db('sprinkles');
  $result = mysql_query("select now()");
  if (!$result) return '';
  $cols = mysql_fetch_array($result);
  return $cols[0];
}

function open_session($username) {
  $result = mysql_query("insert into sessions (username) values ('" . 
                        $username . "')");

  $session_id = mysql_insert_id();
  setcookie('session_id', $session_id);
  return $session_id;
}

function close_session() {
  setcookie('session_id', '');
}

function current_user() {
  $session_id = $_COOKIE["session_id"];
  if (!$session_id) return;
  $sql = "select session_id, username from sessions where session_id = " . 
         $session_id;
  $result = mysql_query($sql);
  if (!$result) { print mysql_error(); return; }
  $cols = mysql_fetch_array($result);
  return $cols[1];
}

function get_users() {
  mysql_connect();
  mysql_select_db('sprinkles');
  $query = mysql_query("select username from users");
  $users = array();
  while ($cols = mysql_fetch_array($query)) {
    array_push($users, array(name => $cols[0]));
  }
  return $users;
}

function redirect($url) {
  header('Location: ' . $url, true, 302);
}

mysql_connect();
mysql_select_db('sprinkles');

?>
