<?php
# $Id$

require_once 'hkit.class.php';
require_once 'list.php';

global $h;
$h = new hKit;

# Configuration: to log or not to log?
#   There are three kinds of logging: messages, errors, and debugging output.
#   To disable all three, set $logging to false.
#   To disable messages, set $verbose to false.
#   To disable debugging output, set $debugging to false.
#   (Errors are always logged, unless logging is turned off with $logging.)
$logging = true;
$verbose = true;
$debugging = true;

# dump: for debugging.
function dump($obj) {
  print("<pre>");
  var_dump($obj);
  print("</pre>");
}

function dump_xml($xml) {
  $temp = $xml->saveXML();
  $temp = preg_replace('/</', '&lt;', $temp);
  dump($temp);
}

function debug($msg) {
  global $logging, $debugging;
  if ($logging && $debugging)
    error_log($msg);
}

function message($msg) {
  global $logging, $verbose;
  if ($logging && $verbose)
    error_log($msg);
}

function error($msg) {
  global $logging;
  if ($logging)
    error_log($msg);
}

function assert_well($bool) {
  if (!$bool) { error("Assertion failed"); die("Assertion failed"); }
}

# is_http_url determines whether the given str is an absolute URL in one
# of the HTTP schemes (http: or https:)
function is_http_url($str) {
  return preg_match("|^https?://|", $str);
}

##
## DATE AND TIME
##

# ago: Given two times $time and $now, return a string describing roughly how long 
# ago $time was from $now--for example, "37 minutes ago."
function ago($time, $now) {
  $diff = $now - $time;
  
  if ($diff < 90) return "about a minute ago"; # short circuit the otherwise-good logic below
  
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
  } else if ($diff < 30 * 24 * 3600) {
    $result = (ceil($diff/(7*24*3600)));
    $result .= $result == 1 ? " week" : " weeks";
  } else if ($diff < 365 * 24 * 3600) {
    $result = (ceil($diff/(30*24*3600)));
    $result .= $result == 1 ? " month" : " months";
  } else {
    $result = (ceil($diff/(365.24*24*3600)));
    $result .= $result == 1 ? " year" : " years";
  }
  return ($result . " ago");
}

# mysql_date: given a date in seconds-since-the-epoch, return the 
# MySQL-formatted string for that date.
function mysql_date($date) {
  return gmstrftime('%Y-%m-%d %H:%M:%S', $date);
}

# from_mysql_date: parse the given string as date in MySQL's format and return
# it in seconds since the epoch.
function from_mysql_date($date_str) {
  $date_rec = strptime($date_str, '%Y-%m-%d %H:%M:%S');
  return gmmktime($date_rec['tm_hour'],
                  $date_rec['tm_min'],
                  $date_rec['tm_sec'],
                  $date_rec['tm_mon']+1,
                  $date_rec['tm_mday'],
                  $date_rec['tm_year']);
}

# from_http_date: parse the given string as an HTTP (RFC 2616) Date string
# it in seconds since the epoch.
function from_http_date($date_str) {
  $date_rec = strptime($date_str, '%a, %d %b %Y %H:%M:%S %Z');
  return mktime($date_rec['tm_hour'],
                  $date_rec['tm_min'],
                  $date_rec['tm_sec'],
                  $date_rec['tm_mon']+1,
                  $date_rec['tm_mday'],
                  $date_rec['tm_year']);
}

# get_url returns the contents of the given URL, using a DB-backed cache as 
# necessary. The second parameter chooses between "hard" and "soft" caching.
# With hard-caching on (true), we won't contact the server if we have a cached 
# copy of a resource. With soft-caching (false) we always ask the server 
# whether the resource has changed before using a cached item. In both cases,
# cached items will expire after a fixed timeout period. Configure the timeout 
# period in seconds using the $http_cache_timeout global in config.php.

$request_timer = 0;    # Used for collecting the amt. of time spent making 
                       # API requests.

function invalidate_http_cache($url) {
  mysql_query('delete from http_cache where url = \'' .
              mysql_real_escape_string($url) . '\'');
}

function insert_into($table, $fields) {
  $sql =  'insert into ' . $table . ' (';
  $count = 0;
  foreach ($fields as $field => $value) {
    if ($count++ > 0) $sql .= ', ';
    $sql .= $field;
  }
  $sql .= ') values (';
  $count = 0;
  foreach ($fields as $field) {
    if ($count++ > 0) $sql .= ', ';
    $sql .= '\'' . mysql_real_escape_string($field) . '\'';
  }
  $sql .= ')';
  return mysql_query($sql);
}

function get_url($url, $cache_hard=true) {
  global $http_cache_timeout;

  # Check whether we have a cached response for this URL
  # Note there are two cache timestamps: fetched_on_server is tied to the 
  # server (mothership)'s clock and fetched_on is tied to the local clock.
  # We are careful to compare the local now() against fetched_on and the
  # server's "Date:" header values against fetched_on_server.
  if (!$http_cache_timeout) die("\$http_cache_timeout not set");
  # Expire old cache entries.
  mysql_query('delete from http_cache where fetched_on < now() - ' .
              $http_cache_timeout);
  # Load a valid cache element, if any.
  $sql = 'select content, fetched_on_server from http_cache where url = \'' . 
         mysql_real_escape_string($url) . 
         '\' and fetched_on >= now() - ' . $http_cache_timeout;
  $q = mysql_query($sql);
  if (!$q) die(mysql_error());

  require_once('HTTP/Request.php');
  if ($row = mysql_fetch_row($q)) {
    list($content, $fetched_on) = $row;

    # Under "hard" caching, return the cached data without talking to server.
    if ($cache_hard) return $content;

    # Under "soft" caching, we make a request to ask the server if the resource
    # has changed since our copy.
    
    $fetched_on_http_date = http_date(from_mysql_date($fetched_on));
      
    $req = new HTTP_Request($url);
    $req->addHeader('If-Modified-Since', $fetched_on_http_date);

    global $request_timer;
    $request_timer -= microtime(true);
    $ok = $req->sendRequest();
    $request_timer += microtime(true);
    message("Running request timer: " . $request_timer . "s");  

    if (!PEAR::isError($ok)) {
      $respCode = $req->getResponseCode();
      if (304 == $respCode) {
        # 304 Not Modified; we can use the cached copy.
        message('Cache hit at ' . $url . ' using If-Modified-Since: '
                . $fetched_on_http_date);
        return $content;
      } elseif (200 <= $respCode && $respCode < 300) {
        # Got an OK response, use the data.
        message('Cache refresh at ' . $url . '. If-Modified-Since: '
                . $fetched_on_http_date);
        $content = $req->getResponseBody();
        $fetched_on_server = mysql_date(from_http_date($req->getResponseHeader('Date')));
        mysql_query('delete from http_cache where url = \'' .
                    mysql_real_escape_string($url) . '\'');
        if (!insert_into('http_cache', array('url' => $url,
                                             'content' => $content,
                                             'fetched_on_server' => $fetched_on_server)))
          die(mysql_error());
        return $content;
      }
    }
  } else {
    message("Cache miss; fetching $url");
      
    $req = new HTTP_Request($url);

    global $request_timer;
    $request_timer -= microtime(true);
    $ok = $req->sendRequest();
    $request_timer += microtime(true);
    message("Running request timer: " . $request_timer . "s");  

    if (PEAR::isError($ok)) 
      die("Unknown error trying GET $url");

    $respCode = $req->getResponseCode();
    if (200 <= $respCode && $respCode < 300) {
      # Got an OK response, use it.
      $content = $req->getResponseBody();
      $fetched_on_server = mysql_date(from_http_date($req->getResponseHeader('Date')));
      mysql_query('delete from http_cache where url = \'' .
                  mysql_real_escape_string($url) . '\'');
      if (!insert_into('http_cache', array('url' => $url,
                                           'content' => $content,
                                           'fetched_on_server' => $fetched_on_server)))
        die(mysql_error());
      return $content;
    } else {
       error("GET $url returned $respCode");
       return null;
    }
  }
}

function api_url($path) {    # FIXME: demote to non-class-method
  if (is_http_url($path)) {
    $parts = parse_url($path);
    $path = $parts['path'] . ($parts['query'] ? '?'. $parts['query'] : '')
              . ($parts['fragment'] ? '#' : $parts['fragment']);
  }
  preg_match('|^/*(.*)|', $path, &$temp); # ignore any leading slashes
  $path = $temp[1];
  global $api_root;
  return ($api_root . $path);
}

##
##  COMPANIES
##

## company_hcard: Get company info
function company_hcard($company_id) {
  $company_url = is_http_url($company_id)                    # if it's a fetchable URL,
                     ? $company_id                           #   use it, otherwise
                     : api_url('companies/' . $company_id);  #   it's a sfn:id, so make its URL.
  global $h;
  $company_hcards = $h->getByString('hcard', get_url($company_url));
  return $company_hcards[0];   # return the first card; assume there's just one
}

function company_name($company_id) {
  $card = company_hcard($company_id);
  return $card['fn'];
}

##
##  PEOPLE
##

function get_person_from_string($str) {
  global $h;
  $people = $h->getByString('hcard', $str);
  if (count($people) == 0) return null;
  $person = $people[0];
  return $person;
}

$people_cache = array();

function get_person($url) {
  if ($people_cache[$url]) return $people_cache[$url];
  $person = get_person_from_string(get_url($url));
  $people_cache[$url] = $person;
  return $person;
}


# get_me_person fetches the path /me from the API, using the given Oauth
# credentials. The resulting resource contains a vCard for the user with those
# credentials.
function get_me_person($consumer_data, $session_creds) {
  require_once('HTTP_Request_Oauth.php');
  $me_url = api_url('me');
  $req = new HTTP_Request_Oauth(
                 $me_url,
                 array('consumer_key' => $consumer_data['key'],
                       'consumer_secret' => $consumer_data['secret'],
                       'token' => $session_creds['token'],
                       'token_secret' => $session_creds['token_secret'],
                       'signature_method' => 'HMAC-SHA1',
                       'method' => 'GET'));

  global $request_timer;
  $request_timer -= microtime(true);
  $resp = $req->sendRequest(true, true);
  $request_timer += microtime(true);
  message("Running request timer: " . $request_timer . "s");

  if (!$resp) throw new Exception("Request to $me_url failed. ");
  if ($req->getResponseCode() == 401)
     throw new Exception("Request for /me failed to authorize.");
  return get_person_from_string($req->getResponseBody());
}

##
##  EMPLOYEES
##

## employee_list: Get list of people associated with the company
function employee_list($company_sfnid) {
  assert_well($company_sfnid);
  $employees_url = api_url('companies/' . $company_sfnid . 
                           '/employees');
  global $h;
  $employees_list = $h->getByString('hcard', get_url($employees_url));
  return $employees_list;
}

$employees_cache = array();

$role_names = array('company_admin' => 'Official Rep',
                    'company_rep' => 'Official Rep',
                    'employee' => 'Employee');

## Fetch the people records of all the company's people 
function employees($company_sfnid) {
  global $employees_cache;
  global $role_names;
  if ($employees_cache) return $employees_cache;
  $employee_list = employee_list($company_sfnid);

  $employees_cache = array();
  foreach ($employee_list as $employee_record) {
    $person_record = get_person($employee_record["url"]);
    # Superimpose fields from $person_record onto $employee_record 
    # (incl. person's role).
    foreach ($person_record as $key => $value) {
      $employee_record[$key] = $value;
    }
    # Resolve the role token into a human-readable role name.
    $employee_record['role_name'] = $role_names[$employee_record['role']];
    array_push($employees_cache, $employee_record);
  }
  return $employees_cache;
}

# Given a person URL, find their role at the Sprinkles company and return a
# a pair of the technical role identifier (e.g. company_rep) and the human-
# readable name of the role (e.g. "Official Rep"). Returns null if the given
# person has no role at the current company.
function get_person_role($company_sfnid, $person_url) {
  global $role_names;
  $employees = employees($company_sfnid);
  foreach ($employees as $emp) {
    if ($emp['url'] == $person_url)
      return array($emp['role'], $role_names[$emp['role']]);
  }
  return null;
}

##
##  PRODUCTS
##

# parse_hProduct: given a string in hProduct format, return the product data
# as an array. Supported fields: name, uri and image.
function parse_hProduct($str) {
  $xml = simplexml_load_string($str);
  if (!$xml) return null;
  $root_nodes = $xml->xpath("//*[@class='hproduct']");
  $result = array();
  if ($root_nodes) {
    foreach ($root_nodes as $node) {
      $item = array();
      $elt = $node->xpath(".//*[contains(concat(' ',normalize-space(@class),' '),' name ')]");
      $item['name'] = (string)$elt[0];
      $elt = $node->xpath(".//*[contains(concat(' ',normalize-space(@class),' '),' uri ')]/@href");
      $item['uri'] = (string)$elt[0]['href'][0];
      $elt = $node->xpath(".//*[contains(concat(' ',normalize-space(@class),' '),' image ')]/@src");
      $item['image'] = (string)$elt[0]['src'][0];
      array_push($result, $item);
    }
  }
  return $result;
}

# product_list returns a list of the current company's products; the list 
# generally contains only URLs. Use the method "products" to get a list of 
# products including everything we know about them.
function product_list($company_sfnid) {
  $products_url = api_url('companies/'. $company_sfnid .'/products');

  return parse_hProduct(get_url($products_url));
}

# products: return full product information for each product connected with the
# company identified by $company_sfnid.
function products($company_sfnid) {
  $products_list = product_list($company_sfnid);

  $products = array();
  foreach ($products_list as $product) {
    $url = api_url($product["uri"]);
    if (is_http_url($url))
      $product = parse_hProduct(get_url($url));
    else die("Internal error: strange uri in product: $url");
    assert(is_array($product));
    array_push($products, $product[0]); # Just the first product from each URL is included.
  }
  return $products;
}

$products_cache = array();

# Given a product URL or sfn:id, returns a structure describing the product.
function get_product($url) {
  global $products_cache;
  if ($products_cache[$url]) return ($products_cache[$url]);

  $result = parse_hProduct(get_url($url));
  $result = $result[0];              # Assume just one product in the document.

  $result['tags'] = tags($url . '/tags');
  return ($products_cache[$url] = $result);
}

##
##  TAGS
##

$tags_cache = array();

# tags: return a list of tags as found at the given URL.
function tags($url) {
  global $tags_cache;
  if ($tags_cache[$url]) return $tags_cache[$url];

  $xml = simplexml_load_file($url);  # FIXME: caching.
  $root_nodes = $xml->xpath("//*[@class='tag']");
  $tags = array();
  if ($root_nodes) {
    $result['tags'] = array();
    $tag_elems = $root_nodes[0]->xpath("//*[@class='name']");
    foreach ($tag_elems as $tag_elem) {
      array_push($tags, implode($tag_elem->xpath('child::node()')));
    }
  }
  return ($tags_cache[$url] = $tags);
}

##
## POSTING
##

# oauthed_request($consumer_data, $method, $url, $creds, $req_params,
#                 $query_params)
# makes a request using HTTP method $method to the url $url, supplying OAuth
# credentials from $cred and OAuth consumer data $consumer_data, and using the
# optional request parameters $req_params (as per the Http_Request interface) 
# and HTTP query parameters $query_params. The Oauth credentials should be an 
# associative array having keys 'token' and 'token_secret', containing the 
# corresponding OAuth values. 
function oauthed_request($consumer_data, $method, $url, $creds, $req_params, $query_params) {
  if (!$method) die("Sprinkles method oauthed_request requires method parameter");
  if (!$url) die("Sprinkles method oauthed_request requires URL parameter");
  require_once('HTTP_Request_Oauth.php');
  $req_params['method'] = $method;
  $req_params['token'] = $creds['token'];
  $req_params['token_secret'] = $creds['token_secret'];
  $req_params['consumer_key'] = $consumer_data['key'];
  $req_params['consumer_secret'] = $consumer_data['secret'];
  $req_params['signature_method'] = 'HMAC-SHA1';
  $req_params['timeout'] = $oauth_request_timeout;
  $req = new HTTP_Request_Oauth($url, $req_params);  # TBD: set timeout
  foreach ($query_params as $name => $val) {
    $req->addParam($name, $val);
  }
  $resp = $req->sendRequest(true, true);
  if (!$resp) die("$method request to $url failed.");
  return $req;
}

##
## TOPICS
##

## Topics for the company, filtered by properites specified in $options.
# Presently, you can only filter on one of the axes: product, tag, query, person,
# followed, or related. The at_least parameter gives a minimum number of 
# topics that should be returned. You might get more than this number. 
# Using a smaller number should result in a quicker return.
function topics($company_sfnid, $options, $at_least = 1) {
  if (!singleton(array($options['product'], $options['tag'], $options['query'],
                       $options['person'], $options['followed'], $options['related']))) {
      die('Sprinkles::topics($options) got more than one of these options: '
          .'product, tag, query, person, followed, or related.');
  }
  if ($options['product']) {
    $url_path = is_http_url($options['product']) ?
                    $options['product'] . "/topics" :
                    $url_path = 'products/' . $options['product'] . '/topics';
  } else if ($options['tag']) {
    $options['tag'] = preg_replace('/ /', '_', $options['tag']);
    $url_path = 'tags/' . $options['tag'] . '/topics';
  } else if ($options['person']) {
    $url_path = 'people/' . $options['person'] . '/topics';
  } else if ($options['followed']) {
    $url_path = 'people/' . $options['followed'] . '/followed/topics';
  } else if ($options['related']) {
    $related_to_id = $options['related'];
    $related_to_id = preg_replace('/\?.*/', '', $related_to_id); # FIXME
    $url_path = $related_to_id . '/related';
  } else {
    $url_path = 'companies/' . $company_sfnid . '/topics';
    if ($options['query'])
      $url_path .= '?query=' . urlencode($options['query']);
  }

  # The above options determine an "unfiltered" feed, which is then filtered 
  # by topic style or some other criteria, such as the "unanswered" property.
  # The unfiltered feed has information that we need, though (particularly 
  # the topic totals by style, which should not be filtered to one particular
  # style). Thus we note the URL determined by the above options, before 
  # choosing the filtered URL.
  $unfiltered_feed_url = api_url($url_path);
  
  $extra_params = "";
  if ($options['style']) {
    if ($options['style'] == 'unanswered')
      $extra_params .= '&sort=unanswered';
    else
      $extra_params .= '&style=' . $options['style'];
  }
  if ($options['frequently_asked']) {
    $extra_params .= '&sort=most_me_toos';
  };
  if ($extra_params) {
    if (preg_match('/\?/', $url_path))
      $url_path .= $extra_params;
    else
      $url_path .= '?' . $extra_params;
  }
  $topics_feed_url = api_url($url_path);
  $topics_feed_page_url = $topics_feed_url;
  
  debug($topics_feed_url);
  
  try {
    # == FETCH THE FEED ==
    
    # Atom feeds at Get Satisfaction are divided into pages; each page
    # contains a link/@rel="next" element at the top which points to the
    # next page. We loop, collecting these pages, until we have as many
    # entries as $at_least, or run out of pages.

    $topics = array();

    $first_topics_feed_page = null;
    $prev_page_url = null;
    $last_page_url = "-1";   # placeholder to ensure prev != last when we start.

    while (count($topics) < $at_least && $topics_feed_page_url &&
            $last_page_url != $prev_page_url) {
      $topics_feed_page_str = get_url($topics_feed_page_url, false);

      assert(!!$topics_feed_page_str);
      $topics_feed = new XML_Feed_Parser($topics_feed_page_str);

      # stash the first page of the feed for later reference
      if ($first_topics_feed_page == null) $first_topics_feed_page = $topics_feed;
      
      foreach ($topics_feed->model->getElementsByTagName("link") as $link_elem) {
        if ($link_elem->getAttribute('rel') == 'next')
          $next_page_url = $link_elem->getAttribute('href');
        if ($link_elem->getAttribute('rel') == 'last')
          $last_page_url = $link_elem->getAttribute('href');
      }
      foreach ($topics_feed as $entry) {
        $topic = fix_atom_entry($entry, 'topic');
        array_push($topics, $topic);
      }
      $prev_page_url = $topics_feed_page_url;
      $topics_feed_page_url = $next_page_url;
    }

    if ($unfiltered_feed_url == $topics_feed_url) {
      $unfiltered_feed = $first_topics_feed_page;
    } else {
      $unfiltered_feed_str = get_url($unfiltered_feed_url, false);
      $unfiltered_feed = new XML_Feed_Parser($unfiltered_feed_str);
    }
  
    $totals = topic_totals($unfiltered_feed);
    $totals['this'] = feed_total($first_topics_feed_page);
    
    return(array('topics' => $topics,
                 'totals' => $totals));
  } catch (XML_Feed_Parser_Exception $e) {
    die('Get Satisfaction feed did not pass validation: ' . $e->getMessage());
  }
}

# thread_items takes a feed and a root item (given by ID); it returns the 
# feed in a forest structure where each item's 'replies' property contains 
# the list of its replies. The trees in the forest are the immediate 
# children of the root.
function thread_items($feed, $root) {
  # First, index them all by ID
  $items = array();
  foreach ($feed as $item) {
    $items[$item['id']] = $item;
  }

  # Next, create a pointer from each reply from its parent
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

# flatten_threads takes a forest as returned by thread_items and hoists
# all 2nd-level nodes to the top level. These nodes are still also 
# listed in the 'replies' property of each root node.
function flatten_threads($items) {
  $result = array();
  foreach ($items as $item) {
    array_push($result, $item);
    if ($item['replies']) {
      foreach ($item['replies'] as $reply)
        array_push($result, $reply);
    }
    $result[count($result)-1]['thread_end'] = true;
  }
  return $result;
}

# resolve_authors adds person data to each item in a feed; it expects to
# find an 'author' element having a 'uri' property and will use get_person 
# and get_person_role to fetch further data about that person. The 
# information fetched will be added to the author element, rather than 
# replacing it. This is done in place, on the array passed in. 
# resolve_authors always returns null.
function resolve_authors($company_sfnid, &$items) {
  foreach ($items as &$item) {
    resolve_author($company_sfnid, $item);
  }
  return null;
}

# resolve_author: resolve an individual author (see resolve_authors, above).
function resolve_author($company_sfnid, &$item) {
  $person = get_person($item["author"]["uri"]);
  list($person['role'], $person['role_name']) = 
                     get_person_role($company_sfnid, $item["author"]["uri"]);
  if ($person)
    foreach ($person as $key => $value)
      $item['author'][$key] = $value;
  return null;
}

# resolve_companies adds company data to each item in a feed; it expects to
# find a company_url and it populated the item with the vCard data found at 
# that URL. This is done in place, on the array passed in. There is no return 
# value.
function resolve_companies(&$feed) {
  foreach ($feed as &$item) {
    if ($item['company_url'])
      $item['company'] = company_hcard($item['company_url']);
  }
  return null;
}

$xml_sfn_ns = 'http://api.getsatisfaction.com/schema/0.1';
$xml_opensearch_ns = 'http://a9.com/-/spec/opensearch/1.1/';

function feedTagNS($feed, $ns, $tagName) {  # FIXME: use this.
  return $feed->model->getElementsByTagNameNS($ns, $tagName);
}

# sfn_element: Return an XML node with a given name in the sfn: namespace
function sfn_element($entry, $elem_name) {
  global $xml_sfn_ns;
  return $entry->model->getElementsByTagNameNS($xml_sfn_ns, $elem_name)->item(0);
}

# sfn_element_value: Return the value (content) of an XML node with a given
# name in the sfn: namespace
function sfn_element_value($entry, $elem_name) {
  global $xml_sfn_ns;
  if ($elem = $entry->model->getElementsByTagNameNS($xml_sfn_ns, $elem_name)->item(0))
    return $elem->nodeValue;
}

# sfn_element_present: True if an XML node with the given name in the sfn: 
# namespace is present in the given entry, false otherwise.
function sfn_element_present($entry, $elem_name) {
  global $xml_sfn_ns;
  return !!$entry->model->getElementsByTagNameNS($xml_sfn_ns,
	                                             $elem_name)->item(0);
}

# fix_atom_entry: Given an entry of a feed, which might be either a topic or a reply, fix_atom_entry returns
# a structure that contatins all the data we care about, in a form usable by
# Smarty templates. This can include fetching additional resources.
# Argument $kind is either 'topic' or 'reply'; a few fields are required for 
# one and not the other.
function fix_atom_entry($entry, $kind) {
  $item = array();
  $item['id'] = $entry->id;
    $item['id'] = preg_replace('/\?.*$/', '', $item['id']); # FIXME: this b/c id is not canonical
    if (!$item['id']) die('no id');
    $item['title'] = $entry->title;
  $item['content'] = $entry->content;
  $item['author'] = array();
  $item['author']['name'] = $entry->author();
  $item['author']['uri'] = $entry->author(0, array('param' => 'uri'));

  $item['updated'] = $entry->updated;
  $item['updated_relative'] = ago($entry->updated, time());
  $item['updated_formatted'] = strftime("%B %e, %y", $entry->updated);

  $item['published'] = $entry->published;
  $item['published_relative'] = ago($entry->published, time());
  $item['published_formatted'] = strftime("%B %e, %y", $entry->published);

  # Get various <link> tags. TBD: refactor these
  $link_elems = $entry->model->getElementsByTagName('link');
  foreach ($link_elems as $link_elem) {
    if ($link_elem->getAttribute('rel') == 'company')
      $item['company_url'] = $link_elem->getAttribute('href');
    if ($link_elem->getAttribute('rel') == 'topic_at_sfn')
	  $item['at_sfn'] = $link_elem->getAttribute('href');
    if ($link_elem->getAttribute('rel') == 'replies')
	  $item['replies_url'] = $link_elem->getAttribute('href');
  }

  $in_reply_to_elem = 
                 $entry->model->getElementsByTagName('in-reply-to')->item(0);
  if ($in_reply_to_elem)
    $item['in_reply_to'] = $in_reply_to_elem->nodeValue;
  global $xml_sfn_ns;
  $item['sfn_id'] = sfn_element_value($entry, 'id');
  $item['topic_style'] = sfn_element_value($entry, 'topic_style');
  if ($kind == 'topic' && !$item['topic_style'])
    die("SFN feed problem: no sfn:topic_style on $kind " . $item['id']);
  if ($kind == 'topic') {
    $item['reply_count'] = sfn_element_value($entry, 'reply_count');
    if (!($item['reply_count'] > 0)) $item['reply_count'] = 0;
  }
  $item['follower_count'] = sfn_element_value($entry, 'follower_count');
  $item['star_count'] = sfn_element_value($entry, 'star_count');
  $item['flag_count'] = sfn_element_value($entry, 'flag_count');
  $item['tags'] = preg_split('/, */', sfn_element_value($entry, 'tags'));

  $emotitag_elem = sfn_element($entry, 'emotitag');
  if ($emotitag_elem) {
    $item['emotitag_face'] = $emotitag_elem->getAttribute('face');
    $item['emotitag_severity'] = $emotitag_elem->getAttribute('severity');
    $item['emotitag_emotion'] = trim($emotitag_elem->nodeValue);
  }

  $item['star_promoted'] = sfn_element_present($entry, 'star_promoted');
  $item['company_promoted'] = sfn_element_present($entry, 'company_promoted');
  return $item;
}

# feed_total: Return the value in the opensearch:totalResults element,
# which is the number of items in the entire feed (across Atom pagination).
function feed_total($feed) {
  assert($feed);
  global $xml_opensearch_ns;
  if ($total_results_elem = $feed->model->getElementsByTagNameNS(
                                        $xml_opensearch_ns,
                                        'totalResults'))
    return $total_results_elem->item(0)->nodeValue;
  else return null;
}

# topic_totals: Return a record that contains the count for various different
# types of elements, based on elements contained in the feed element. These
# counts go across the entire feed (across Atom pagination).
function topic_totals($feed) {
  assert($feed);

  $result = array();

  $result['all'] = feed_total($feed);
  $result['talk'] = sfn_element_value($feed, 'talk_count');
  $result['ideas'] = sfn_element_value($feed, 'idea_count');
  $result['problems'] = sfn_element_value($feed, 'problem_count');
  $result['questions'] = sfn_element_value($feed, 'question_count');
  $result['unanswered'] = sfn_element_value($feed, 'unanswered_count');
  return $result;     
}
  
# Given a list of topic replies, return a pair of (a) just those that are 
# company-promoted and (b) just those that are people-promoted (star-promoted).
function filter_promoted($replies) {
  $company_promoted = array();
  $star_promoted = array();
  foreach ($replies as $reply) {
    if ($reply['company_promoted']) array_push($company_promoted, $reply);
    if ($reply['star_promoted']) array_push($star_promoted, $reply);
  }
  return array($company_promoted, $star_promoted);
}

# company_partition: Given a list of topics, partition it into the ones that 
# are associated with the given company and those that aren't. The comparison
# is based on the array of URLs found in the company hCard.
function company_partition($company_hcard, $topics) {
  $company_urls = $company_hcard['url'];

  $company_topics = array();
  $noncompany_topics = array();
  foreach ($topics as $topic) {
    if (member($topic['company_url'], $company_urls)) {
      array_push($company_topics, $topic);
    } else {
      array_push($noncompany_topics, $topic);
    }
  }
  return array($company_topics, $noncompany_topics);
}

function topic($company_sfnid, $topic_id) {
  $url = $topic_id;

  $feed_raw = get_url($url, false);
  if (!$feed_raw) die("Failed to load topic at $url.");
  $topic_feed = new XML_Feed_Parser($feed_raw);

  if (!$topic_feed) die("Couldn't get topic feed from $url");

  # Add stuff to the raw feed data using fix_atom_entry
  $items = array();
  foreach ($topic_feed as $entry) {
    $item = fix_atom_entry($entry, 'reply');
    array_push($items, $item);
  }

  # Collect information about the participants in this topic
  $authors = array();
  $employees = array();
  foreach ($items as $item) {
    $authors[$item['author']['uri']]++;
    list($role, $role_name) = get_person_role($company_sfnid, $item['author']['uri']);
    if ($role) {
      $employees[$item['author']['uri']] = $item['author'];
      $employees[$item['author']['uri']]['role'] = $role;
    }
  }
  $official_reps = array();
  foreach ($employees as $emp) {
    $emp_data = get_person($emp['uri']);
    $emp = superimpose($emp, $emp_data);
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

# cmop_by_updated: compare two objects by their 'updated' fields, returning a 
# value either negative, zero, or positive, for use with sorting functions.
# Useful for sorting synthetic feeds.
function cmp_by_updated($a, $b) {
  return $b['updated'] - $a['updated'];
}

?>