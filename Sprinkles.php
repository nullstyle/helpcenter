<?php
# $Id$

require_once 'XML/Feed/Parser.php';
require_once 'config.php';
require_once 'hkit.class.php';

# take: return a list of the first $n elements from $list
function take($n, $list) {
  if (!is_array($list)) throw new Exception("Non-array passed to 'take'");
  $result = array();
  $i = 0;
  foreach ($list as $item) {
    if (++$i > $n) { break; }
      array_push($result, $item);
  }
  return $result;
}

# take_range: return a list of elements from $list numbered $lo through $hi-1
function take_range($lo, $hi, $list) {
  $result = array();
  $i = 0;
  foreach ($list as $item) {
    if ($i >= $hi) { break; }
    if ($i >= $lo) {
      array_push($result, $item);
    }
    $i++;
  }
  return $result;
}

function ago($time, $now) {
  $diff = $now - $time;
  
  if ($diff < 60) {
    $result = $diff;
    $result .= $result == 1 ? " second" : " seconds";
  } else if ($diff < 3600) {
    $result = (ceil($diff/60));
    $result .= $result == 1 ? " minute" : " minutes";
  } else if ($diff < 24 * 3600) {
    $result = (ceil($diff/3600));
    $result .= $result == 1 ? " hour" : " hours";
  } else if ($diff < 7 * 24 * 3600) {
    $result = (ceil($diff/(24*3600)));
    $result .= $result == 1 ? " day" : " days";
  } else if ($diff < 365 * 24 * 3600) {
    $result = (ceil($diff/(30*24*3600)));
    $result .= $result == 1 ? " month" : " months";
  } else {
    $result = (ceil($diff/(365.24*24*3600)));
    $result .= $result == 1 ? " year" : " years";
  }
  return ($result . " ago");
}

function is_http_url($str) {
  # Naive regex for detecting URLs in the http schemes.
  # FIXME: need something more robust.
  return preg_match("|^https?://|", $str);
}

function dump($obj) {
  print("<pre>");
  var_dump($obj);
  print("</pre>");
}

function unbollocks($str) {  ## CURSE CURSE CURSE
  ## unencodes strings that are needlessly encoded by default in PHP < 6.0
  return preg_replace(array("/\\\\'/", "/\\\\\\\\/", "/\\\\0/"),
                      array("'", "\\", "\x00"),
                      $str);
}

function request_param($name) {
  if ($_GET[$name]) {
    $result = $_GET[$name];
  } else if ($_POST[$name]) {
    $result = $_POST[$name];
  }
  $php_version_six = false; # FIXME: get the real version
  if ($php_version_six)
    return $result;
  else
    return unbollocks($result);
}

$http_cache_timeout = 600; # seconds

$cache_hits = 0;    # For diagnostic purposes; TBD: offer a way to examine these.
$cache_misses = 0;

function get_url($url) {
  global $http_cache_timeout;
  if (!$http_cache_timeout) die("\$http_cache_timeout not set");
  mysql_query('delete from http_cache where fetched_on < now() - ' .
              $http_cache_timeout);
  $sql = 'select content from http_cache where url = \'' . $url . 
         '\' and fetched_on >= now() - ' . $http_cache_timeout;
  $q = mysql_query($sql);
  if (!$q) die(mysql_error());
  if ($row = mysql_fetch_row($q)) {
    global $cache_hits;
    $cache_hits++;
    return $row[0];
  } else {
    global $cache_misses;
    $cache_misses++;
    error_log("Cache miss; fetching $url");
    $content = file_get_contents($url);
    if (!mysql_query('insert into http_cache (url, content) values (\'' . 
                mysql_real_escape_string($url) . '\', \'' . 
                mysql_real_escape_string($content) . '\')'))
      die(mysql_error());
    return $content;
  }
}


function cmp_by_updated($a, $b) {
  return $b['updated'] - $a['updated'];
}

global $h;
$h = new hKit;
    
global $robust_mode;   # Causes Sprinkles to filter a feed even if it 
                       # is supposed to be filtered already.
# $robust_mode = true;
$robust_mode = false;

$xml_sfn_ns = 'http://api.getsatisfaction.com/schema/0.1';
$xml_opensearch_ns = 'http://a9.com/-/spec/opensearch/1.1/';

class Sprinkles {

  var $company_id;
  var $employees;  # Cache this in the object because it's used frequently.
  var $people_cache = array();

  var $role_names = array('company_admin' => 'Official Rep',
                          'company_rep' => 'Official Rep',
                          'employee' => 'Employee');

  function Sprinkles() {
    $result = mysql_query('select company_id from site_settings');
    if ($result) {
      $row = mysql_fetch_array($result);
      $this->company_id = $row[0];
    }
  }

  ## Get company info
  function company_hcard($company_id = null) {
    if ($company_id == null) $company_id = $this->company_id;
    $company_url = is_http_url($company_id)
                       ? $company_id
                       : $this->api_url('companies/' . $this->company_id);
    global $h, $quick_mode;
    if ($quick_mode) {
      $company_hcards = $h->getByString('hcard', file_get_contents($company_url));
    } else {
#      $company_hcards = $h->getByURL('hcard', $company_url);
      $company_hcards = $h->getByString('hcard', get_url($company_url));
    }
    return $company_hcards[0];
  }

  function set_company($company) {
    $sql = 'update site_settings set ' . 
           'company_id = \'' . $company . '\'';
    #print $sql;
    return mysql_query($sql);
  }

  function set_site_settings($settings) {
    $sql = 'update site_settings set ';
    $i = 0;
    foreach ($settings as $name => $value) {
      if ($i++ > 0) $sql .= ', ';
      $sql .= $name . '=\'' . mysql_real_escape_string($value) . '\'';
    }
    error_log("Updating site settings: $sql");
    return mysql_query($sql);
  }

  function add_admin_users($admins) {
    foreach ($admins as $admin) {
      mysql_query('insert into users (username) values (\'' . $admin . '\')');
    }
  }

  function set_admin_users($admins) {
    if (!mysql_query('delete from users')) die(mysql_error());
    foreach ($admins as $admin) {
      mysql_query('insert into users (username) values (\'' . $admin . '\')');
    }
  }

  function company_name() {
    if ($company_id == null) $company_id = $this->company_id;
    $card = $this->company_hcard($company_id);
    return $card['fn'];
  }

  function sfn_element($entry, $elem_name) {
    global $xml_sfn_ns;
    if ($elem = $entry->model->getElementsByTagNameNS($xml_sfn_ns, $elem_name)->item(0))
      return $elem->nodeValue;
  }

  function sfn_element_present($entry, $elem_name) {
    global $xml_sfn_ns;
    return !!$entry->model->getElementsByTagNameNS($xml_sfn_ns,
                                                   $elem_name)->item(0);
  }

  function fix_atom_reply($entry) {
    return $this->fix_atom_entry($entry, 'reply');
  }

  # Argument $kind is one of topic, reply
  function fix_atom_entry($entry, $kind) {
    $item = array();
    $item['id'] = $entry->id;
    if (!$item['id']) die('no id');
    $item['title'] = $entry->title;
    $item['content'] = $entry->content;
    $item['author'] = array();
    $item['author']['name'] = $entry->author();
    $item['author']['uri'] = $entry->author(0, array('param' => 'uri'));
    $person = $this->get_person($item["author"]["uri"]);
    list($person['role'], $person['role_name']) = 
                                $this->get_person_role($item["author"]["uri"]);
    if ($person) {
      foreach ($person as $key => $value) {
        $item['author'][$key] = $value;
      }
    }
    $item['updated'] = $entry->updated;
    $item['updated_relative'] = ago($entry->updated, time());
    $item['updated_formatted'] = strftime("%B %e, %y", $entry->updated);

    $item['published'] = $entry->published;
    $item['published_relative'] = ago($entry->published, time());
    $item['published_formatted'] = strftime("%B %e, %y", $entry->published);

    $link_elems = $entry->model->getElementsByTagName('link');
    foreach ($link_elems as $link_elem) {
        if ($link_elem->getAttribute('rel') == 'company')
	  $item['company_url'] = $link_elem->getAttribute('href');
    }

    $in_reply_to_elem = 
                    $entry->model->getElementsByTagName('in-reply-to')->item(0);
    if ($in_reply_to_elem)
      $item['in_reply_to'] = $in_reply_to_elem->nodeValue;
    global $xml_sfn_ns;
    if (!$xml_sfn_ns) die("no satisfaction namespace!");
    $item['topic_style'] = $this->sfn_element($entry, 'topic_style');
    if ($kind == 'topic' && !$item['topic_style'])
      die("SFN feed problem: no sfn:topic_style");
    $item['reply_count'] = $this->sfn_element($entry, 'reply_count');
    $item['star_count'] = $this->sfn_element($entry, 'star_count');
    $item['flag_count'] = $this->sfn_element($entry, 'flag_count');
    $emotitag_elem = $entry->model->getElementsByTagNameNS(
                                        $xml_sfn_ns, 'emotitag')->item(0);
    if ($emotitag_elem) {
      $item['emotitag_face'] = $emotitag_elem->getAttribute('face');
      $item['emotitag_severity'] = $emotitag_elem->getAttribute('severity');
      $item['emotitag_emotion'] = trim($emotitag_elem->nodeValue);
    }

    $item['star_promoted'] = $this->sfn_element_present($entry, 'star_promoted');
    $item['company_promoted'] = $this->sfn_element_present($entry, 'company_promoted');
    return $item;
  }

# A new strategy for avoiding fetching extra resources.
# TBD: Port this to other elements that require fetching external resources.
  function resolve_companies(&$feed) {
    foreach ($feed as &$item) {
      if ($item['company_url'])
        $item['company'] = $this->company_hcard($item['company_url']);
    }
  }

  function dashboard_topics($person) {
    $started = $this->topics(array('person' => $person));
    $followed = $this->topics(array('followed' => $person));
    $items = array_merge($started['topics'],
                         $followed['topics']);
    usort($items, cmp_by_updated);
    return $items;
  }

  ## Topics for the company, filter by values in $options
  function topics($options) {
#    TBD: check input, that there's a unique option amongst these.
#    list($options['product'], $options['tag'], $options['query'],
#         $options['person'], $options['followed'], $options['related'];
    if ($options['product']) {
      $url_path = is_http_url($options['product']) ?
                      $options['product'] . "/topics" :
                      $url_path = 'products/' . $options['product'] . '/topics';
    } else if ($options['tag']) {
      $url_path = 'tags/' . $options['tag'] . '/topics';
    } else if ($options['person']) {
      $url_path = 'people/' . $options['person'] . '/topics';
    } else if ($options['followed']) {
      $url_path = 'people/' . $options['followed'] . '/followed/topics';
    } else if ($options['related']) {
      $url_path = $options['related'] . '/related';
    } else {
      $url_path = 'companies/'.$this->company_id.'/topics';
      if ($options['query'])
        $url_path .= '?query=' . urlencode($options['query']);
    }
    $url_path .= '?';
    if ($options['style']) {
      if ($options['style'] == 'unanswered')
        $url_path .= '&sort=unanswered';
      else
        $url_path .= '&style=' . $options['style'];
    }
    if ($options['frequently_asked']) {
      $url_path .= '&sort=most_me_toos';
    };
    $topics_feed_url = $this->api_url($url_path);
#    print "getting topics feed $topics_feed_url.";
    try {
      $feed = new XML_Feed_Parser(file_get_contents($topics_feed_url));
      $topics = array();
      foreach ($feed as $entry) {
        $topic = $this->fix_atom_entry($entry, 'topic');
        # use 'notags' for a faster response;
        # TBD use the 'resolve' method
        if (!$options['notags']) {
          $topic_tags_url = $topic['id'] . '/tags';
          $topic['tags'] = $this->tags($topic_tags_url);
        }
        array_push($topics, $topic);
      }
#      dump($topics);

# FIXME: expand robust mode to cover more options
      global $robust_mode;
      if ($robust_mode && $options['style']) {
        # Filter the topics down to those of the given style
        $new_topics = array();
        foreach ($topics as $t) {
          if ($t['topic_style'] == $options['style']) {
            array_push($new_topics, $t);
          }
        }
        $topics = $new_topics;
      }

      return(array('topics' => $topics,
                   'totals' => $this->topic_totals($feed)));
    } catch (XML_Feed_Parser_Exception $e) {
      die('Satisfaction feed did not pass validation: ' . $e->getMessage());
    }
  }

  function topic_totals($feed) {
    $result = array();
    global $xml_opensearch_ns;
    if ($total_results_elem = $feed->model->getElementsByTagNameNS(
                                          $xml_opensearch_ns,
                                          'totalresults')) {
      $result['all_count'] = $total_results_elem->nodeValue;
    }
    $result['idea_count'] = $this->sfn_element($feed, 'idea_count');
    $result['talk_count'] = $this->sfn_element($feed, 'talk_count');
    $result['problem_count'] = $this->sfn_element($feed, 'problem_count');
    $result['question_count'] = $this->sfn_element($feed, 'question_count');
    $result['unanswered_count'] = $this->sfn_element($feed, 'unanswered_count');
    return $result;     
  }

  function thread_items($feed, $root) {
    # First, index them all by ID
    $items = array();
    foreach ($feed as $item) {
      $items[$item['id']] = $item;
    }

    # Next, point to each sub-reply from its parent
    foreach ($items as $item) {
      if ($item['in_reply_to'])
        if ($items[$item['in_reply_to']]) {
          # List subordinates as field of parent
          if (!is_array($items[$item['in_reply_to']]))
            $items[$item['in_reply_to']]['replies'] = array();
          if (!is_array($items[$item['in_reply_to']]['replies']))
            $items[$item['in_reply_to']]['replies'] = array();
          array_push($items[$item['in_reply_to']]['replies'], $item);
        }
    }


    # Then, remove each sub-reply from the toplevel stream
    foreach ($items as $item) {
      if ($item['in_reply_to'])
        if ($item['in_reply_to'] != $root) {
          unset($items[$item['id']]);
        }
    }
    return $items;
  }

  function flatten_threads($items) {
    $result = array();
    foreach ($items as $item) {
      array_push($result, $item);
      if ($item['replies']) 
        foreach ($item['replies'] as $reply) {
          array_push($result, $reply);
        }
    }
    return $result;
  }

  function tags($url) {
    if ($this->tags_cache[$url]) return $this->tags_cache[$url];
    $tags = array();
#    error_log "Getting $url";
# FIXME: getting tags this way until hkit is fixed
    $xml = simplexml_load_file($url);
    $root_nodes = $xml->xpath("//*[@class='tag']");
    if ($root_nodes) {
      $result['tags'] = array();
      $tag_elems = $root_nodes[0]->xpath("//*[@class='name']");
      foreach ($tag_elems as $tag_elem) {
        array_push($tags, implode($tag_elem->xpath('child::node()')));
      }
    }
    $this->tags_cache[$url] = $tags;
    return $tags;
  }

  function topic($id) {
    global $quick_mode, $cache_dir;
    $url = $quick_mode ?
         $cache_dir . 'topics/299.cache' : 
         $id;
# TBD: add check that $url is rooted at a sanctioned base URL
    assert(!!$url);

#    error_log "Getting $url";

    $topic_feed = new XML_Feed_Parser(file_get_contents($url));

    if (!$topic_feed) die("Couldn't get topic feed from $url");

# FIXME: needs to return metadata on the topic, not just the entries
    $items = array();
    foreach ($topic_feed as $entry) {
      $item = $this->fix_atom_entry($entry, 'reply');
      array_push($items, $item);
    }

    $authors = array();
    $employees = array();
    foreach ($items as $item) {
      $authors[$item['author']['url']]++;
      list($role, $role_name) = $this->get_person_role($item['author']['url']);
      if ($role) {
        $employees[$item['author']['url']] = $item['author'];
        $employees[$item['author']['url']]['role'] = $role;
      }
    }
    $official_reps = array();
    foreach ($employees as $emp) {
      if ($emp['role'] == 'company_rep' || $emp['role'] == 'company_admin')
        array_push($official_reps, $emp);
    }
    $particip = array('people' => count($authors),
                      'employees' => count($employees),
                      'official_reps' => $official_reps,
                      'count_official_reps' => count($official_reps));
      
    return array('replies' => $items,
                 'particip' => $particip,
                 'tags' => $tags);
  }

  ## Get list of people associated with the company
  # TBD: un-factor this, inline it into employees.
  function employee_list() {
    $people_url = $this->api_url('companies/'.$this->company_id.'/employees');
    global $h, $quick_mode;
    if ($quick_mode) {
      $people_list = $h->getByString('hcard', file_get_contents($people_url));
    } else {
#      $people_list = $h->getByURL('hcard', $people_url);
      $people_list = $h->getByString('hcard', get_url($people_url));
    }
    if (!$people_list) { die("no people list"); }
    return $people_list;
  }

  ## Fetch the people records of all the company's people 
  function employees() {
    if ($this->employees) return $this->employees;
    $employee_list = $this->employee_list();
    $this->employees = array();
    global $h;
    foreach ($employee_list as $employee_record) {
      $person_record = $this->get_person($employee_record["url"]);
      # Superimpose fields from $person_record onto $employee_record 
      # (incl. person's role).
      foreach ($person_record as $key => $value) {
        $employee_record[$key] = $value;
      }
      # Resolve the role token into a human-readable role name.
      $employee_record['role_name']= $this->role_names[$employee_record['role']];
      array_push($this->employees, $employee_record);
    }
    return $this->employees;
  }

  function get_person_role($person_url) {
# TBD: parametrize this for the company?
    $employees = $this->employees();
    foreach ($employees as $emp) {
      if ($emp['url'] == $person_url)
        return array($emp['role'], $this->role_names[$emp['role']]);
    }
    return null;
  }
    
  function get_person_from_doc($doc) {
    # TBD: refactor this together with the get_person function.
    global $h;
    $people = $h->getByString('hcard', $doc);
    if (count($people) == 0) {
      return null;
    }
    assert(count($people) == 1);   # There should only be one person at this URL.
    $person = $people[0];
    $this->people_cache[$url] = $person;
    return $person;
  }

  function get_person($url) {
    if ($this->people_cache[$url]) return $this->people_cache[$url];
    global $h;
    error_log("Getting person from $url");
    $person = $h->getByString('hcard', get_url($url));
    if (count($person) == 0) throw new Exception("No person at $url");
    assert(count($person) == 1);   # There should only be one person at this URL.
    $person = $person[0];
    $this->people_cache[$url] = $person;
    assert($person);
    return $person;
  }

  function product_api_url($id) {
    $path = is_http_url($id) ? $id : 'products/' . $id;
    return $this->api_url($path);
  }

  var $product_cache = array();

  function get_product($url) {
    if ($this->product_cache[$url]) return ($this->product_cache[$url]);
    global $h;
#    print "Getting product from $url<br />";
#    $result = $h->getByURL('hproduct', $url);
    $result = $h->getByString('hproduct', get_url($url));
    $result = $result[0];   # Assume just one product in the document.

    $result['tags'] = $this->tags($url . '/tags');
    $this->product_cache[$url] = $result;
    return $result;
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
      $products_list = $h->getByString('hproduct', get_url($products_url));
    }
    return $products_list;
  }

  ## Fetch the product records
  function products() {
    $products = array();
    $products_list = $this->product_list();

    global $h, $quick_mode, $cache_dir;
    foreach ($products_list as $product) {
      $url = $this->api_url($product["uri"]);
      if (is_http_url($url)) {
#        print "Getting product data from $url<br />";
        $product = $h->getByURL('hproduct', $url);
        $product = $h->getByString('hproduct', get_url($url));
      }
      else  $product = $h->getByString('hproduct', file_get_contents($url));
      assert(is_array($product));
      array_push($products, $product[0]);
    }
    assert(is_array($products));
    return $products;
  }

   function filter_promoted($replies) {
     $company_promoted = array();
     $star_promoted = array();
     foreach ($replies as $reply) {
       if ($reply['company_promoted']) array_push($company_promoted, $reply);
       if ($reply['star_promoted']) array_push($star_promoted, $reply);
     }
     return array($company_promoted, $star_promoted);
   }

  function company_partition($topics) {
    $company_topics = array();
    $noncompany_topics = array();
    $company_hcard = $this->company_hcard();
    $company_id = $company_hcard['url'];
    foreach ($topics as $topic) {
      # BUG: company_urls are not normalized.
      if ($topic['company_url'] == $company_id) {
        array_push($company_topics, $topic);
      } else {
        array_push($noncompany_topics, $topic);
      }
    }
    return array($company_topics, $noncompany_topics);
  }
  
  function api_url($path) {
    # print "Getting api_url for $path";
    # if (is_http_url($path)) return $path;   # FIXME: Do we want to trust URLs?
    if (is_http_url($path)) {
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
  
  
  function open_session($token) {
#    $result = mysql_query("insert into user_sessions (username) values ('" . 
#                          $username . "')");
  
#    $session_id = mysql_insert_id();
    setcookie('session_id', $token);
    return $session_id;
  }
  
  function close_session() {
    setcookie('session_id', '');
  }

  function oauthed_request($method, $url, $creds, $params, $qParams) {
    require_once('HTTP_Request_Oauth.php');
    $params['method'] = $method;
    $params['token'] = $creds['token'];
    $params['token_secret'] = $creds['token_secret'];
    $params['consumer_key'] = 'lmwjv4kzwi27';
    $params['consumer_secret'] = 'fiei6iv61jnoukaq1aylwd8vcmnkafrs';
    $params['signature_method'] = 'HMAC-SHA1';
    $req = new HTTP_Request_Oauth($url, $params);
    foreach ($qParams as $name => $val) {
#      $req->addQueryString($name, $val);
      $req->addParam($name, $val);
    }
    $resp = $req->sendRequest(true, true);
    if (!$resp) die("$method request to $url failed. ");
    if ($req->getResponseCode() == 401) {
      error_log($req->getResponseBody());
      die("$method request for $url failed to authorize.");
    }
    return $req;    
  }
 
#FIXME: how do we signal error?
  function get_me_resource($creds) {
    require_once('HTTP_Request_Oauth.php');
    $me_url = 'http://api.getsatisfaction.com/me';
    error_log("Getting /me with OAuth. Token: " . $creds['token'] . " " . 
                $creds['token_secret']);
    $req = new HTTP_Request_Oauth($me_url,
                   array('consumer_key' => 'lmwjv4kzwi27',
                         'consumer_secret' => 'fiei6iv61jnoukaq1aylwd8vcmnkafrs',
                         'token' => $creds['token'],
                         'token_secret' => $creds['token_secret'],
                         'signature_method' => 'HMAC-SHA1',
                         'method' => 'GET'));
    $resp = $req->sendRequest(true, true);
    if (!$resp) die("Request to $me_url failed. ");
    if ($req->getResponseCode() == 401)
       die("Request for /me failed to authorize.");

    return $this->get_person_from_doc($req->getResponseBody());
  }

  function current_user_creds() {
    $session_id = $_COOKIE["session_id"];
    if (!$session_id) return;
    error_log("looking up creds for token $session_id");
    $sql = "select username,token,token_secret from oauth_tokens where token='". 
           $session_id . "'";
    # error_log($sql);
    $result = mysql_query($sql);
    if (!$result) { die(mysql_error()); }
    $cols = mysql_fetch_array($result);
    error_log("got " . $cols[1] . " " . $cols[2]);
    if (!$cols) return NULL;
    return $cols;
  }
  
  function current_user() {
    list($username, $token, $token_secret) = $this->current_user_creds();
    if (!$token || !$token_secret) return null;
#    if (!$username) {
# TBD: store the whole /me resource in the database.
      $me_person = $this->get_me_resource(array('token' => $token,
                                                'token_secret'=> $token_secret));
      if (!$me_person) return null;
      $username = $me_person['canonical_name'];
      $result = mysql_query("update oauth_tokens set username = '" . 
                  $username
                  . "' where token = '" . $session_id . "'");
      if (!$result) die("Failed to store current user's name in database.");

      $query = mysql_query("select * from users where username = '" .
                           $me_person['canonical_name'] . "'");
      if ($row = mysql_fetch_array($query))
        $me_person['sprinkles_admin'] = true;
#    }
    return $me_person;
  }
  
  function current_username() {
    $me = $this->current_user();
    return $me['fn'];
  }

  function get_users() { # TBD: rename this to admins()
    $query = mysql_query("select username from users");
    $users = array();
    while ($cols = mysql_fetch_array($query)) {
      array_push($users, array(username => $cols[0]));
    }
    return $users;
  }
  
  function user_is_admin() {
    # TBD: optimize
    $query = mysql_query("select username from users");
    $username = $this->current_username();
    while ($cols = mysql_fetch_array($query)) {
      if ($cols[0] == $username) return true;
    }
    return false;
  }

  function site_background_color() {
    $sql = 'select background_color from site_settings';
    $result = mysql_query($sql);
    list($background_color) = mysql_fetch_array($result);
    return $background_color;
  }

  function site_configured() {
    $sql = 'select configured from site_settings';
    $row = mysql_fetch_array(mysql_query($sql));
    return ($row[0] == 'Y');
  }

  function site_contact_info() {
    $sql = 'select contact_email, contact_phone, contact_address, map_url '.
           'from site_settings';
    $result = mysql_query($sql);
    list($contact_email, $contact_phone, $contact_address, $map_url) = 
                                                mysql_fetch_array($result);
    return array('contact_email' => $contact_email,
                 'contact_phone' => $contact_phone,
                 'contact_address' => $contact_address, 
                 'map_url' => $map_url);
  }

  function site_logo() {
    $sql = 'select logo_data '.
           'from site_settings';
    $result = mysql_query($sql);
    list($logo_data) = mysql_fetch_array($result);
    return $logo_data;
  }

  function add_std_hash_elems($smarty) {
    $current_user = $this->current_user();
    # Standard stash items
    $smarty->assign(array('background_color' => $this->site_background_color(),
                          'company_name' => $this->company_name(),
                          'current_user' => $current_user,
                          'user_name' => $current_user['fn'],
                          'site_configured' => $this->site_configured()));
  }
}

function redirect($url) {
  header('Location: ' . $url, true, 302);
}

# FIXME: connect params don't have any effect.

$mysql = mysql_connect($mysql_connect_params, $mysql_username, $mysql_password);
if (!$mysql) die("Stopping: Couldn't connect to MySQL database.");

function sprinkles_root_url() {
  $q = mysql_query('select sprinkles_root_url from site_settings');
  if (!$q) die("Database error getting Sprinkles root URL: " . mysql_error());
  $row = mysql_fetch_row($q);
  return $row[0];
}

mysql_select_db('sprinkles');

?>
