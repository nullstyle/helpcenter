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

$api_calls  = 0;
$cached_api_calls  = 0;

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

function invalidate_http_cache($url) {
  $result = mysql_query('delete from http_cache where url = \'' .
                        mysql_real_escape_string($url) . '\'');
  if (!$result) { error("Database error invalidating cache: " . mysql_error()); }
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
  global $api_calls;
  global $cached_api_calls;
  
  # Check whether we have a cached response for this URL
  # Note there are two cache timestamps: fetched_on_server is tied to the 
  # server (mothership)'s clock and fetched_on is tied to the local clock.
  # We are careful to compare the local now() against fetched_on and the
  # server's "Date:" header values against fetched_on_server.
  if (!$http_cache_timeout) throw new Exception("\$http_cache_timeout not set");
  # Expire old cache entries.
  mysql_query('delete from http_cache where fetched_on < now() - ' .
              $http_cache_timeout);
  # Load a valid cache element, if any.
  $sql = 'select content, fetched_on_server from http_cache where url = \'' . 
         mysql_real_escape_string($url) . 
         '\' and fetched_on >= now() - ' . $http_cache_timeout;
  $q = mysql_query($sql);
  if (!$q) throw new Exception("Getting cache, got database error: " . mysql_error());


  require_once('HTTP/Request.php');
  if ($row = mysql_fetch_row($q)) {
    list($content, $fetched_on) = $row;

    # Under "hard" caching, return the cached data without talking to server.
    if ($cache_hard) { message("Hard cache hit at $url"); return $content; }

    # Under "soft" caching, we make a request to ask the server if the resource
    # has changed since our copy.
    $fetched_on_http_date = date(DATE_RFC1123, from_mysql_date($fetched_on));
    
    $req = new HTTP_Request($url);
    $req->addHeader('If-Modified-Since', $fetched_on_http_date);

    $request_timer -= microtime(true);
    $ok = $req->sendRequest();
    $request_timer += microtime(true);
    $cached_api_calls = $cached_api_calls + 1;

    if (!PEAR::isError($ok)) {
      $respCode = $req->getResponseCode();
      if (304 == $respCode) {
        # 304 Not Modified; we can use the cached copy.
        message('Cache hit at ' . $url . ' using If-Modified-Since: ' .
                $fetched_on_http_date . 
                "Request timer: $request_timer" . 's');
        return $content;
      } elseif (200 <= $respCode && $respCode < 300) {
        # Got an OK response, use the data.
        message('Cache refresh at ' . $url . ' If-Modified-Since: ' .
                $fetched_on_http_date . 
                '. Request timer: ' . $request_timer . 's');
        $content = $req->getResponseBody();
        $fetched_on_server = mysql_date(from_http_date($req->getResponseHeader('Date')));
        mysql_query('delete from http_cache where url = \'' .
                    mysql_real_escape_string($url) . '\'');
        if (!insert_into('http_cache', array('url' => $url,
                                             'content' => $content,
                                             'fetched_on_server' => $fetched_on_server)))
          throw new Exception("Database error writing to HTTP cache: " . mysql_error());
        return $content;
      }
    } else { throw new Exception("Error while GETing $url ($ok)"); }
  } else {
    $req = new HTTP_Request($url);

    $request_timer -= microtime(true);
    $ok = $req->sendRequest();
    $request_timer += microtime(true);
    $api_calls = $api_calls + 1;
    message("Cache miss at $url Request timer: " . $request_timer . "s");  

    if (PEAR::isError($ok)) 
      throw new Exception("Unknown error trying GET $url");

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
        throw new Exception("Database error writing to HTTP cache: " . mysql_error());
      return $content;
    } else {
       error("GET $url returned $respCode");
       return null;
    }
  }
}

function api_url($path) {
  if (is_http_url($path)) {
    $parts = parse_url($path);
    $path = $parts['path'] . ($parts['query'] ? '?'. $parts['query'] : '')
              . ($parts['fragment'] ? '#' : $parts['fragment']);
  }
  preg_match('|^/*(.*)|', $path, $temp);  # (ignore any leading slashes)
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
  try {
    $company_hcards = $h->getByString('hcard', get_url($company_url));
  } catch (Exception $e) {
    invalidate_http_cache($company_url);
    throw $e;
  }
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
  try {
    $person = get_person_from_string(get_url($url));
  } catch (Exception $e) {
    invalidate_http_cache($url);
    throw $e;
  }
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

  $request_timer -= microtime(true);
  $resp = $req->sendRequest(true, true);
  $request_timer += microtime(true);
  message("Bypassed cache at $me_url Request timer: " . $request_timer . "s");

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
  $employees_url = api_url('companies/' . $company_sfnid . 
                           '/employees');
  global $h;
  try {
    $employees_list = $h->getByString('hcard', get_url($employees_url));
  } catch (Exception $e) {
    invalidate_http_cache($employees_url);
    throw $e;
  }
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
    # Resolve the role token into a human-readable role name.
    $employee_record['role_name'] = $role_names[$employee_record['role']];
    array_push($employees_cache, $employee_record);
  }
  return $employees_cache;
}

# Given a person URL, find their role at the given company and return a
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
# Filter products by 'makes' to get products made by the current company 
# or 'related' to get products that have been associated with that company
function product_list($company_sfnid) {
  $products_url = api_url('companies/'. $company_sfnid .'/products?filter=makes');
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
    else throw new Exception("Internal error: strange uri in product: $url");
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
  $raw = get_url($url);
  $xml = simplexml_load_string($raw);
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
  if (!$method) throw new Exception("oauthed_request() requires method parameter");
  if (!$url) throw new Exception("oauthed_request() requires URL parameter");
  require_once('HTTP_Request_Oauth.php');
  $req_params['method'] = $method;
  $req_params['token'] = $creds['token'];
  $req_params['token_secret'] = $creds['token_secret'];
  $req_params['consumer_key'] = $consumer_data['key'];
  $req_params['consumer_secret'] = $consumer_data['secret'];
  $req_params['signature_method'] = 'HMAC-SHA1';
  $req_params['timeout'] = $oauth_request_timeout;
  message("Sending $method request to $url");
  $req = new HTTP_Request_Oauth($url, $req_params);  # TBD: set timeout
  foreach ($query_params as $name => $val) {
    $req->addParam($name, $val);
  }
  $resp = $req->sendRequest(true, true);
  if (!$resp) throw new Exception("$method request to $url failed.");
  return $req;
}

function get_oauth_request_token($consumer_data) {
  global $sfn_root;
  $oauth_req = new HTTP_Request_OAuth(
                     $sfn_root . 'api/request_token',
                     array('consumer_key' => $consumer_data['key'],
                           'consumer_secret' => $consumer_data['secret'],
                           'signature_method' => 'HMAC-SHA1',
                           'method' => 'GET'));
  $resp = $oauth_req->sendRequest(true, true);
  list($token, $secret) = $oauth_req->getResponseTokenSecret();
  return array($token, $secret);
}

function get_oauth_access_token($consumer_data, $request_token, $request_token_secret) {
  global $sfn_root;
  $oauth_req = new HTTP_Request_OAuth(
                     $sfn_root . 'api/access_token',
                     array('consumer_key' => $consumer_data['key'],
                           'consumer_secret' => $consumer_data['secret'],
                           'token' => $request_token,
                           'token_secret' => $request_token_secret,
                           'signature_method' => 'HMAC-SHA1',
                           'method' => 'GET'));

  $resp = $oauth_req->sendRequest(true, true);

  list($token, $secret) = $oauth_req->getResponseTokenSecret();

  return array($token, $secret);
}

function oauth_authorization_url($token, $callback_url) {
  global $sfn_root;
  return $sfn_root . 'api/authorize?oauth_token='. $token
           . '&oauth_callback=' . urlencode($callback_url);

}

##
## TOPICS
##

## Topics for the company, filtered by properites specified in $options.
# Presently, you can only filter on one of the axes: product, tag, query, person,
# followed, or related. The at_least parameter gives a minimum number of 
# topics that should be returned. You might get more than this number. 
# Using a smaller number should result in a quicker return.
function topics($company_sfnid, $options) {
  
  if (!singleton(array($options['product'], $options['tag'], $options['query'],
                       $options['person'], $options['followed'], $options['related']))) {
      throw new Exception('topics($options) got more than one of these options: '
          .'product, tag, query, person, followed, or related.');
  }
  if ($options['product']) {
    $url_path = is_http_url($options['product']) ?
                    $options['product'] . "/topics" :
                    $url_path = 'products/' . $options['product'] . '/topics';
  } else if ($options['tag']) {
    $options['tag'] = preg_replace('/ /', '_', $options['tag']);
    $url_path = 'companies/' . $company_sfnid . '/tags/' . $options['tag'] . '/topics';
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
  
  if ($options['page']) {
    $extra_params .= '&page=' . $options['page'];
  };  
  
  if ($options['limit']) {
    $extra_params .= '&limit=' . $options['limit'];
  };
   
  if ($options['sort']) {
    $extra_params .= '&sort=' . $options['sort'];
  };
  
  if ($extra_params) {
    if (preg_match('/\?/', $url_path))
      $url_path .= $extra_params;
    else
      $url_path .= '?' . $extra_params;
  }
  $topics_feed_url = api_url($url_path);
  
    # == FETCH THE FEED ==
  
    # Atom feeds at Get Satisfaction are divided into pages; each page
    # contains a link/@rel="next" element at the top which points to the
    # next page. We loop, collecting these pages, until we have as many
    # entries as $at_least, or run out of pages.

    $topics = array();

    $topics_feed_page_str = get_url($topics_feed_url, false);

    assert(!!$topics_feed_page_str);
    try {
      $topics_feed = new XML_Feed_Parser($topics_feed_page_str);
    } catch (XML_Feed_Parser_Exception $e) {
      invalidate_http_cache($topics_feed_page_url);
      throw new Exception("Get Satisfaction feed at $topics_feed_page_url not valid: "
                          . $e->getMessage());
    }

    foreach ($topics_feed as $entry) {
      $topic = fix_atom_entry($entry, 'topic');
      array_push($topics, $topic);
    }
  
    $totals = topic_totals($topics_feed);
    $totals['this'] = feed_total($topics_feed);
    
    return(array('topics' => $topics,
                 'totals' => $totals));
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

  # Next, create a pointer to each reply from its parent
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
    if (!$item['id']) throw new Exception('No ID in item $entry');
    $item['title'] = $entry->title;
  $item['content'] = $entry->content;
  $item['author'] = array();
  $item['author']['name'] = $entry->author();
  $item['author']['uri'] = $entry->author(0, array('param' => 'uri'));
  $item['author']['url'] = $item['author']['uri'];
  $item['author']['photo'] = $entry->author(0, array('param' => 'avatar'));
  $item['author']['canonical_name'] = $entry->author(0, array('param' => 'canonical_name'));

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
    throw new Exception("SFN feed problem: no sfn:topic_style on $kind " . $item['id']);
  if ($kind == 'topic') {
    $item['reply_count'] = sfn_element_value($entry, 'reply_count');
    if (!($item['reply_count'] > 0)) $item['reply_count'] = 0;
  }
  $item['follower_count'] = sfn_element_value($entry, 'follower_count');
  $item['me_too_count'] = sfn_element_value($entry, 'me_too_count');
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
  if (!$feed_raw) throw new Exception("Failed to load topic feed at $url.");
  try {
    $topic_feed = new XML_Feed_Parser($feed_raw);
    if (!$topic_feed) throw new Exception("Invalid feed at $url");
  } catch (XML_Feed_Parser_Exception $e) {
    invalidate_http_cache($url);
    throw new Exception("Invalid feed at $url");
  }

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
