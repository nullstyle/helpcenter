<?php
# $Id$

#require_once 'XML/Feed/Parser.php';
require_once 'setup.php';

function take($n, $list) {
  assert(is_array($list));
  $result = array();
  $i = 0;
  foreach ($list as $item) {
    if ($i++ > $n) { break; }
    array_push($result, $item);
  }
  return $result;
}

function take_range($lo, $hi, $list) {
  $result = array();
  $i = 0;
  foreach ($list as $item) {
    if ($i++ > $hi) { break; }
    if ($i >= $lo) {
      array_push($result, $item);
    }
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
  } else if ($diff < 365 * 24 * 3600) {
    return (ceil($diff/(30*24*3600))) . " months ago";
  } else {
    return (ceil($diff/(365.24*24*3600))) . " years ago";
  }
}

function is_url($str) {
  # Naive regex for detecting URLs in the http schemes.
  return preg_match("|^https?://|", $str);
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
    
global $robust_mode;   # Causes Sprinkles to filter a feed even if it 
                       # is supposed to be filtered already.
# $robust_mode = true;
$robust_mode = false;

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

  ## Topics for the company, filter by values in $options
  function topics($options) {
    assert(!($options['product'] && $options['tag']));
    if ($options['product']) {
      $url_path = is_url($options['product']) ?
                      $options['product'] . "/topics" :
                      $url_path = 'products/' . $options['product'] . '/topics';
    } else if ($options['tag']) {
      $url_path = 'tags/' . $options['tag'] . '/topics';
    } else {
      $url_path = 'companies/'.$this->company_id.'/topics';
    }
    if ($options['style']) {
      if ($options['style'] == 'unanswered')
        $url_path .= '?sort=unanswered';
      else
        $url_path .= '?style=' . $options['style'];
    }
    $topics_feed_url = $this->api_url($url_path);
    # print "getting topics feed $topics_feed_url.";
    $atom = new myAtomParser($topics_feed_url);
    $topics = $atom->output['FEED'][""]["ENTRY"];      # FIXME extra level here.
    if (!$topics) { return array(); }

    foreach ($topics as &$topic) {
      $topic['UPDATED_EPOCH'] = strtotime($topic['UPDATED']);
      $topic['UPDATED_RELATIVE'] = ago(strtotime($topic['UPDATED']), time());
    }

    global $robust_mode;
    if ($robust_mode && $options['style']) {
      # Filter the topics down to those of the given style
      $new_topics = array();
      foreach ($topics as $t) {
        if ($t['SFN:TOPIC_STYLE'] == $options['style']) {
          array_push($new_topics, $t);
        }
      }
      $topics = $new_topics;
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
    if ($quick_mode) {
      # FIXME
      $person = $h->getByString('hcard',
                          file_get_contents($cache_dir . "people-40451.html"));
    } else {
      $person = $h->getByURL('hcard', $url);
    }
    return $person;
  }

  function product_api_url($id) {
    $path = is_url($id) ? $id : 'products/' . $id;
    return $this->api_url($path);
  }

  function get_product($url) {
    global $h;
    $result = $h->getByURL('hproduct', $url);
    return $result[0];   # Assume just one product in the document.
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
      $url = $this->api_url($product["uri"]);
      if (is_url($url))
        $product = $h->getByURL('hproduct', $url);
      else  $product = $h->getByString('hproduct', file_get_contents($url));
      assert(is_array($product));
      array_push($products, $product[0]);
    }
    assert(is_array($products));
    return $products;
  }
  
  function api_url($path) {
    # print "Getting api_url for $path";
    # if (is_url($path)) return $path;   # FIXME: Do we want to trust URLs?
    if (is_url($path)) {
      $parts = parse_url($path);
      $path = $parts['path'] . ($parts['query'] ? '?'. $parts['query'] : '')
                . ($parts['fragment'] ? '#' : $parts['fragment']);
    }
    global $cache_dir;
    global $api_root;
    global $quick_mode;
    preg_match('|^/*(.*)|', $path, &$temp);
    $path = $temp[1];
    # print " as $path";
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
