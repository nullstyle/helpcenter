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

function mysql_now() {
  # for testing DB connection
  mysql_connect();
  mysql_select_db('sprinkles');
  $result = mysql_query("select now()");
  if (!$result) return '';
  $cols = mysql_fetch_array($result);
  return $cols[0];
}
  
require_once('hkit.class.php');
global $h;
$h = new hKit;
    
class Sprinkles {

  var $company_id;

  function Sprinkles($company_id) {
     $this->company_id = $company_id;
  }

  ## Get company info
  function company_hcard() {
    $company_url = $this->api_url('companies/' . $this->company_id);
    global $h, $quick_mode;
    if ($quick_mode) {
      $company_hcards = $h->getByString('hcard', file_get_contents($company_url));
    } else {
      $company_hcards = $h->getByURL('hcard', $company_url);
    }
    return $company_hcards[0];
  }

  ## All topics for the company
  function topics() {
    $topics_feed_url= $this->api_url('companies/'.$this->company_id.'/topics');
    # print "getting topics feed $topics_feed_url.";
    $atom = new myAtomParser($topics_feed_url);
    foreach ($atom->output as $feed) {
      $topics = $feed[""]["ENTRY"];        # FIXME extra level here.
    }
    return $topics;
  }

  ## Get list of people associated with the company
  function people_list() {
    $people_url = $this->api_url('companies/'.$this->company_id.'/people');
    global $h, $quick_mode;
    if ($quick_mode) {
      $people_list = $h->getByString('hcard',
                                       file_get_contents($people_url));
    } else {
      $people_list = $h->getByURL('hcard', $people_url);
    }
    if (!$people_list) { print "no people list"; die(1); }
    return $people_list;
  }

  ## Fetch the people records of all the company's people 
  function people() {
    $people_list = $this->people_list();
    $people = array();
    global $h;
    foreach ($people_list as $person) {
    if ($quick_mode) {
        $url = api_url("people/40451");
        $person_record = $h->getByString('hcard', $url);
      } else {
        $person_record = $h->getByURL('hcard', $person["url"]);
      }
      array_push($people, $person_record[0]);
    }
    return $people;
  }
    
  function get_person($url) {
    global $h;
    $person = array();
    if ($quick_mode) {
      $person = $h->getByString('hcard',
                          file_get_contents($cache_dir . "people-40451.html"));
    } else {
      $person = $h->getByURL('hcard', $url);
    }
    return $person;
  }

  ## Return a list of the company's products
  function product_list() {
    $products_url = $this->api_url('companies/'. $this->company_id .'/products');

    global $h, $quick_mode;
    $products_list = array();
    if ($quick_mode) {
      $products_list = $h->getByString('hproduct',
                                       file_get_contents($products_url));
    } else {
      $products_list = $h->getByURL('hproduct', $products_url);
    }
    return $products_list;
  }

  ## Fetch the product records
  function products() {
    $products = array();
    $products_list = $this->product_list();
    if (!$products_list) { print "Couldn't get product list"; die(); }
    global $h, $quick_mode, $cache_dir;
    foreach ($products_list as $product) {
      if ($quick_mode) {
        $product = $h->getByString('hproduct',
                       file_get_contents($cache_dir.'/products/6681.cache'));
      } else {
        $product = $h->getByURL('hproduct', $product["uri"]);
      }
      assert(is_array($product));
      array_push($products, $product[0]);
    }
    assert(is_array($products));
    return $products;
  }
  
  function api_url($path) {
    global $cache_dir;
    global $api_root;
    global $quick_mode;
    return ($quick_mode ?
      ($cache_dir . $path . ".cache") :
      ($api_root . $path));
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
}

function redirect($url) {
  header('Location: ' . $url, true, 302);
}

mysql_connect();
mysql_select_db('sprinkles');

?>
