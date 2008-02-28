<?php
# $Id$

####### Sprinkles.php ######
##
##  This file defines the core logic for interacting with the 
##  getsatisfaction.com web API, logic shared throughout the Sprinkles 
##  application, and general utility functions.
##
##  See individual methods and functions for detailed documentation.
##
##  NOTE: You can expect the interface to this file to change.
##
########
require_once 'XML/Feed/Parser.php';
require_once 'hkit.class.php';

require_once 'config.php';

# Smarty configuration
require_once('smarty/Smarty.class.php');
$smarty = new Smarty();
$smarty->template_dir = $sprinkles_dir . '/templates/';
$smarty->compile_dir  = $sprinkles_dir . '/templates_c/';
$smarty->config_dir   = $sprinkles_dir . '/configs/';
$smarty->cache_dir    = $sprinkles_dir . '/cache/';

global $h;
$h = new hKit;

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

# singleton($array) is true if there is no more than one non-null item in $array
function singleton($array) {
  return count(array_filter($array)) <= 1;
}

# uniq($array) is a copy of $array with consecutive duplicates removed.
function uniq($array) {
  $deck = null;
  $result = array();
  foreach ($array as $item) {
    if ($item != $deck)
      array_push($result, $item);
    $deck = $item;
  }
  return $result;
}

# PHP's array_search is useless, as it may return 0 when the element
# is first in the list, and 0 is indistinguishable from false.
function member($x, $list) {
  foreach ($list as $y) if ($x==$y) return true;
  return false;
}

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

function is_http_url($str) {
  # Naive regex for detecting URLs in the http schemes.
  # FIXME: need something more robust.
  return preg_match("|^https?://|", $str);
}

# dump: for debugging.
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

function php_major_version() {
  $version_str = phpversion();
  list($major) = explode('.', $version_str);
  return $major;
}

$needs_unbollocks = php_major_version() < 6;

# request_param returns the HTTP request parameter, whether it lies in the 
# query string or the POST data.
function request_param($name) {
  if ($_GET[$name]) {
    $result = $_GET[$name];
  } else if ($_POST[$name]) {
    $result = $_POST[$name];
  }
  global $needs_unbollocks;
  # Versions before 6 do an unwarranted backslashing of request parameters;
  # Here we reverse that so we get the real data.
  if ($needs_unbollocks && get_magic_quotes_gpc())
    return unbollocks($result);
  else
    return $result;
}

function feedTagNS($feed, $ns, $tagName) {  # FIXME: use this.
  return $feed->model->getElementsByTagNameNS($ns, $tagName);
}

# get_url returns the contents of the given URL, using a DB-backed cache as necessary
# This function blindly caches everything, regardless of headers and such, so use it
# only when you don't expect the underlying resource to change during the timeout period.
# Configure the timeout period in seconds using the $http_cache_timeout global.
$http_cache_timeout = 3600; # seconds

$cache_hits = 0;    # For diagnostic purposes; TBD: offer a way to examine these.
$cache_misses = 0;

$request_timer = 0;

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
	global $request_timer;
    $request_timer -= microtime(true);
    $content = file_get_contents($url);  # TBD: recognize errors here
    $request_timer += microtime(true);
    error_log("Running request timer: " . $request_timer . "s");
    if ($content) {
      if (!mysql_query('insert into http_cache (url, content) values (\'' . 
                  mysql_real_escape_string($url) . '\', \'' . 
                  mysql_real_escape_string($content) . '\')'))
        die(mysql_error());
    }
    return $content;
  }
}

function parse_hproduct($str) {
#  $str = preg_replace('/&(?!amp;)/', '&amp;', $str); # stopgap needed when $str is broken
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

# compare two objects by their 'updated' fields, returning a value either 
# negative, zero, or positive, for use with sorting functions.
function cmp_by_updated($a, $b) {
  return $b['updated'] - $a['updated'];
}

global $robust_mode;   # Causes Sprinkles to filter a feed even if it 
                       # is supposed to be filtered already.
# $robust_mode = true;
$robust_mode = false;

$xml_sfn_ns = 'http://api.getsatisfaction.com/schema/0.1';
$xml_opensearch_ns = 'http://a9.com/-/spec/opensearch/1.1/';

function sfn_element($entry, $elem_name) {
  global $xml_sfn_ns;
  return $entry->model->getElementsByTagNameNS($xml_sfn_ns, $elem_name)->item(0);
}

function sfn_element_value($entry, $elem_name) {
  global $xml_sfn_ns;
  if ($elem = $entry->model->getElementsByTagNameNS($xml_sfn_ns, $elem_name)->item(0))
    return $elem->nodeValue;
}

function sfn_element_present($entry, $elem_name) {
  global $xml_sfn_ns;
  return !!$entry->model->getElementsByTagNameNS($xml_sfn_ns,
	                                             $elem_name)->item(0);
}


class Sprinkles {

  var $company_sfnid;
  # Cache a few things here in case they're needed more than once in a request.
  var $employees_cache;
  var $people_cache = array();
  var $products_cache = array();

  var $role_names = array('company_admin' => 'Official Rep',
                          'company_rep' => 'Official Rep',
                          'employee' => 'Employee');

  function Sprinkles() {
    $result = mysql_query('select company_id from site_settings');
    if ($result) {
      $row = mysql_fetch_array($result);
      $this->company_sfnid = $row[0];
    }
  }

  ## Get company info
  function company_hcard($company = null) {
    if ($company == null) $company = $this->company_sfnid;
    $company_url = is_http_url($company)                          # if it's a fetchable URL,
                       ? $company                                 # use it, otherwise
                       : $this->api_url('companies/' . $company); # it's a sfn:id, so make its URL.
    global $h;
    $company_hcards = $h->getByString('hcard', get_url($company_url));
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

  # add_admin_users adds the given list of usernames to the list of users that
  # have admin rights on this installation, leaving any current admins 
  # undisturbed. Does not signal any failure. Duplicate admin records may be 
  # created.
  function add_admin_users($admins) {
    foreach ($admins as $admin) {
      mysql_query('insert into admins (username) values (\'' . $admin . '\')');
    }
  }

  # set_admin_users sets the list of users that have admin rights on this
  # Sprinkles installation to the given list of usernames, clearing out any 
  # that alread have admin rights. Does not signal any failure.
  function set_admin_users($admins) {
    if (!mysql_query('delete from admins')) die(mysql_error());
    foreach ($admins as $admin) {
      mysql_query('insert into admins (username) values (\'' . $admin . '\')');
    }
  }

  function company_name() {
    $card = $this->company_hcard($this->company_sfnid);
    return $card['fn'];
  }

  # Given an entry of a feed, either a topic or a reply, fix_atom_entry returns
  # a structure that contatins all the data we care about, in a form usable by
  # Smarty templates. This can include fetching additional resources.
  # Argument $kind is either 'topic' or 'reply'; a few fields are reuired for 
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

# TBD: Get link/@rel=replies content.
#
# http://uk3.php.net/manual/en/function.xpath-eval-expression.php
#
#foreach ($link_elems as $elem) {
#  foreach ($elem->attributes as $attr)
#    if ($attr->name == 'rel' && $attr->value == 'replies') {
#      die ($elem->content);
#    }
#}

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
    $item['star_count'] = sfn_element_value($entry, 'star_count');
    $item['flag_count'] = sfn_element_value($entry, 'flag_count');

    $emotitag_elem = sfn_element($entry, 'emotitag');
	#dump($emotitag_elem->nodeValue);
    if ($emotitag_elem) {
      $item['emotitag_face'] = $emotitag_elem->getAttribute('face');
      $item['emotitag_severity'] = $emotitag_elem->getAttribute('severity');
      $item['emotitag_emotion'] = trim($emotitag_elem->nodeValue);
    }

    $item['star_promoted'] = sfn_element_present($entry, 'star_promoted');
    $item['company_promoted'] = sfn_element_present($entry, 'company_promoted');
    return $item;
  }
  
  function fix_atom_reply($entry) {
    return $this->fix_atom_entry($entry, 'reply');
  }

  function topic_totals($feed) {
    $result = array();
    global $xml_opensearch_ns;
    if ($total_results_elem = $feed->model->getElementsByTagNameNS(
                                          $xml_opensearch_ns,
                                          'totalresults')) {
      $result['all'] = $total_results_elem->nodeValue;
    }
    $result['ideas'] = sfn_element_value($feed, 'idea_count');
    $result['talk'] = sfn_element_value($feed, 'talk_count');
    $result['problems'] = sfn_element_value($feed, 'problem_count');
    $result['questions'] = sfn_element_value($feed, 'question_count');
    $result['unanswered'] = sfn_element_value($feed, 'unanswered_count');
    return $result;     
  }

  ## Topics for the company, filtered by properites specified in $options.
  # Presently, you can only filter on one of the axes: product, tag, query, person,
  # followed, or related.
  function topics($options) {
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
      $url_path = 'companies/'.$this->company_sfnid.'/topics';
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
    try {
      global $request_timer;
      $request_timer -= microtime(true);
      $feed_raw = file_get_contents($topics_feed_url);
      $request_timer += microtime(true);
      error_log("Running request timer: " . $request_timer . "s");
      $feed = new XML_Feed_Parser($feed_raw);
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

      # Robust mode filters the entries in a feed according to the criteria 
      # that we requested with. The feed should already be filtered that way, 
      # so this is just a safeguard.
      # FIXME: expand robust mode to cover more options.
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

  # A user's dashboard collects a variety of kinds of topics; dashboard_topics
  # fetchs them all and merges the results into one chronological feed.
  function dashboard_topics($person) {
    $started = $this->topics(array('person' => $person));
    $followed = $this->topics(array('followed' => $person));
    $items = array_merge($started['topics'],
                         $followed['topics']);
    usort($items, cmp_by_updated);
    $items = uniq($items);
    return $items;
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

  # resolve_companies adds company data to each item in a feed; it expects to
  # find a company_url and it populated the item with the vCard data found at 
  # that URL.
# TBD: Port this to other elements that require fetching external resources.
  function resolve_companies(&$feed) {
    foreach ($feed as &$item) {
      if ($item['company_url'])
        $item['company'] = $this->company_hcard($item['company_url']);
    }
  }

  # tags returns a list of tags as found at the given URL.
  function tags($url) {
    if ($this->tags_cache[$url]) return $this->tags_cache[$url];
    # error_log "Getting $url";

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
    $this->tags_cache[$url] = $tags;
    return $tags;
  }

  function topic($id) {
    $url = $id;

    assert(!!$url);
# TBD: add check that $url is rooted at a sanctioned base URL

    # error_log "Getting $url";

	global $request_timer;
	$request_timer -= microtime(true);
	$feed_raw = file_get_contents($url);
	if (!$feed_raw) die("Failed to load topic at $url.");
	$request_timer += microtime(true);
	error_log("Running request timer is " . $request_timer . "s");
    $topic_feed = new XML_Feed_Parser($feed_raw);

    if (!$topic_feed) die("Couldn't get topic feed from $url");

    # Add stuff to the raw feed data using fix_atom_entry
    $items = array();
    foreach ($topic_feed as $entry) {
      $item = $this->fix_atom_entry($entry, 'reply');
      array_push($items, $item);
    }

    # Collect information about the participants in this topic
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
  function employee_list() {
    $people_url = $this->api_url('companies/'.$this->company_sfnid.'/employees');
    global $h;
    $people_list = $h->getByString('hcard', get_url($people_url));
    return $people_list;
  }

  ## Fetch the people records of all the company's people 
  function employees() {
    if ($this->employees_cache) return $this->employees_cache;
    $employee_list = $this->employee_list();
    $this->employees_cache = array();
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
      array_push($this->employees_cache, $employee_record);
    }
    return $this->employees_cache;
  }

  # Given a person URL, find their role at the Sprinkles company and return a
  # a pair of the technical role identifier (e.g. company_rep) and the human-
  # readable name of the role (e.g. "Official Rep"). Returns null if the given
  # person has no role at the current company.
  function get_person_role($person_url) {
    $employees = $this->employees();
    foreach ($employees as $emp) {
      if ($emp['url'] == $person_url)
        return array($emp['role'], $this->role_names[$emp['role']]);
    }
    return null;
  }
  
  function get_person_from_string($str) {
    global $h;
    $people = $h->getByString('hcard', $str);
    if (count($people) == 0) {
      return null;
    }
    $person = $people[0];
    return $person;
  }

  function get_person($url) {
    if ($this->people_cache[$url]) return $this->people_cache[$url];
    global $h;
    $person = $this->get_person_from_string(get_url($url));
    $this->people_cache[$url] = $person;
    return $person;
  }

  # product_api_url gives the URL for a product based on either of its
  # sfn:id or its id.
  function product_api_url($id) {
    $path = is_http_url($id) ? $id : 'products/' . $id;
    return $this->api_url($path);
  }

  # Given a product URL or sfn:id, returns a structure describing the product.
  function get_product($url) {
    if ($this->products_cache[$url]) return ($this->products_cache[$url]);
    global $h;
    $result = parse_hproduct(get_url($url));
    $result = $result[0];   # Assume just one product in the document.

    $result['tags'] = $this->tags($url . '/tags');
    $this->products_cache[$url] = $result;
    return $result;
  }

  # product_list returns a list of the current company's products; the list 
  # generally contains only URLs. Use the method "products" to get a list of 
  # products including everything we know about them.
  function product_list() {
    $products_url = $this->api_url('companies/'. $this->company_sfnid .'/products');

    global $h;
    $products_list = array();
    $products_list = parse_hproduct(get_url($products_url));
    return $products_list;
  }

  # products returns full product information for each product connected with the
  # current company.
  function products() {
    $products = array();
    $products_list = $this->product_list();

    global $h;
    foreach ($products_list as $product) {
      $url = $this->api_url($product["uri"]);
      if (is_http_url($url)) {
        $product = parse_hproduct(get_url($url));
      }
      else die("strange uri in product: $url");
      assert(is_array($product));
      array_push($products, $product[0]);
    }
    assert(is_array($products));
    return $products;
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

  # Given a list of topics, partition it into the ones that are associated
  # on the current company and those that aren't.
  function company_partition($topics) {
    $company_topics = array();
    $noncompany_topics = array();
    $company_hcard = $this->company_hcard();
    $company_sfnid = $company_hcard['url'];
    foreach ($topics as $topic) {
      # BUG: company_urls are not normalized.
      if ($topic['company_url'] == $company_sfnid) {
        array_push($company_topics, $topic);
      } else {
        array_push($noncompany_topics, $topic);
      }
    }
    return array($company_topics, $noncompany_topics);
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
  
  var $nascent_session_id;   # the session ID, when we've just set it and have not yet received a cookie
  
  function open_session($token) {
    if (!$token) die("Call to open_session with blank token: '$token'");
# TBD: fetch /me resource and stash its info in the session table.
    setcookie('session_id', $token);
    $this->nascent_session_id = $token;
    return $token;
  }
  
  function close_session($session_id=null) {
    if (!$session_id) $session_id = $_COOKIE['session_id'];
    if (!$session_id) $session_id = $this->nascent_session_id;
    mysql_query("delete from oauthed_tokens where token = " .  $session_id);
    setcookie('session_id', '');
  }

  # oauthed_request($method, $url, $creds, $req_params, $query_params) makes
  # a request using HTTP method $method to the url $url, supplying oauth
  # credentials from $cred and using the optional request parameters
  # $req_params (as expected by the Http_Request class) and HTTP query 
  # parameters $query_params. The Oauth credentials should be an associative 
  # array having keys 'token' and 'token_secret', containing the corresponding
  # Oauth values. The Oauth consumer_key and consumer_secret are those given
  # when Sprinkles was installed.
  function oauthed_request($method, $url, $creds, $req_params, $query_params) {
    if (!$method) die("Sprinkles method oauthed_request requires method parameter");
    if (!$url) die("Sprinkles method oauthed_request requires URL parameter");
    require_once('HTTP_Request_Oauth.php');
    $consumer_data = $this->oauth_consumer_data();
    $req_params['method'] = $method;
    $req_params['token'] = $creds['token'];
    $req_params['token_secret'] = $creds['token_secret'];
    $req_params['consumer_key'] = $consumer_data['key'];
    $req_params['consumer_secret'] = $consumer_data['secret'];
    $req_params['signature_method'] = 'HMAC-SHA1';
    $req = new HTTP_Request_Oauth($url, $req_params);
    foreach ($query_params as $name => $val) {
      $req->addParam($name, $val);
    }
    $resp = $req->sendRequest(true, true);
    if (!$resp) die("$method request to $url failed. ");
    if ($req->getResponseCode() == 401) {
      error_log("Got response from API: " . $req->getResponseBody());
      # The session was no good; close it so the user can create a new one on next login
      close_session($creds['token']);
      die("$method request for $url failed to authorize."); # FIXME: give the user an error page
    }
    return $req;    
  }
 
   # get_me_resource fetches the path /me from the API, using the given Oauth
   # credentials, and that resource contains a vCard for the user with those
   # credentials.
#FIXME: how do we signal error?
  function get_me_resource($creds) {
    require_once('HTTP_Request_Oauth.php');
    $me_url = $this->api_url('me');
#     error_log("Getting /me with OAuth. Token: " . 
#                 $creds['token'] . " " . 
#                 $creds['token_secret']);
    $consumer_data = $this->oauth_consumer_data();
    $req = new HTTP_Request_Oauth($me_url,
                   array('consumer_key' => $consumer_data['key'],
                         'consumer_secret' => $consumer_data['secret'],
                         'token' => $creds['token'],
                         'token_secret' => $creds['token_secret'],
                         'signature_method' => 'HMAC-SHA1',
                         'method' => 'GET'));
    global $request_timer;
    $request_timer -= microtime(true);
    $resp = $req->sendRequest(true, true);
    $request_timer += microtime(true);
    error_log("Running request timer: " . $request_timer . "s");
    if (!$resp) die("Request to $me_url failed. ");
    if ($req->getResponseCode() == 401)
       die("Request for /me failed to authorize.");
    return $this->get_person_from_string($req->getResponseBody());
  }

  function current_user_creds() {
    $session_id = $this->nascent_session_id;
    if (!$session_id) $session_id = $_COOKIE["session_id"];
    if (!$session_id) return null;
    # error_log("looking up creds for token $session_id");
#print "Going to session table.";
#    dump($session_id);
    $sql = "select username,token,token_secret from sessions where token='". 
           $session_id . "'";
    $result = mysql_query($sql);
    if (!$result) { die(mysql_error()); }
    $cols = mysql_fetch_array($result);
    # error_log("got " . $cols[1] . " " . $cols[2]);
    if (!$cols) { setcookie('session_id', ''); return null; } # Cookie session was not in DB; clear it
    return array('username' => $cols[0],
                 'token' => $cols[1],
                 'token_secret' => $cols[2]);
  }
  
  # current_user returns the vCard of the currently logged-in user. If it needs to
  # fetch the user's vCard, it will store it in the database.
  # The returned vCard also contains a boolean field 'sprinkles_admin' indicating
  # whether this user has the privilege to administer this Sprinkles installation.
  function current_user() {
    $creds = $this->current_user_creds();
    if (!$creds || !$creds['token'] || !$creds['token_secret']) return null;
    $username = $creds['username'];
#    if (!$username) {  # FIXME: store the whole /me resource in the database, use it here.
      $me_person = $this->get_me_resource($creds);
	  if (!$me_person) return null;
	  $username = $me_person['canonical_name'];
	  if (!$username) die("Current user had no canonical_name");
	  $result = mysql_query("update sessions set username = '" . $username
	  					  . "' where token = '" . $creds['token'] . "'");
	  if (!$result) die("Failed to store current user's name in database.");
	
	  $query = mysql_query("select * from admins where username = '" .
                           $me_person['canonical_name'] . "'");
      if ($row = mysql_fetch_array($query))
        $me_person['sprinkles_admin'] = true;
#    }
#    dump($me_person);
    return $me_person;
  }
  
  function current_username() {
    $me = $this->current_user();
    return $me['canonical_name'];
  }

  function get_users() { # TBD: rename this to admins()
    $query = mysql_query("select username from admins");
    $users = array();
    while ($cols = mysql_fetch_array($query)) {
      array_push($users, array(username => $cols[0]));
    }
    return $users;
  }
  
  function user_is_admin() {
    # TBD: optimize
    $query = mysql_query("select username from admins");
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

  var $oauth_consumer_data;
   
  function oauth_consumer_data() {
    if (!$this->oauth_consumer_data) {
      $sql = 'select oauth_consumer_key, oauth_consumer_secret '.
             'from site_settings';
      $result = mysql_query($sql);
      list($key, $secret) = mysql_fetch_array($result);
      $this->oauth_consumer_data = array('key' => $key, 'secret' => $secret);
    }
    return $this->oauth_consumer_data;
  }
  
  function site_logo() {
    $sql = 'select logo_data from site_settings';
    $result = mysql_query($sql);
    list($logo_data) = mysql_fetch_array($result);
    return $logo_data;
  }

  function site_logo_link() {
    $sql = 'select logo_link from site_settings';
    $result = mysql_query($sql);
    list($logo_link) = mysql_fetch_array($result);
    return $logo_link;
  }

  # add_std_hash_elems takes a Smarty object and sets some common variables 
  # that are used on every page.
  function add_std_hash_elems($smarty) {
    $current_user = $this->current_user();
    $logo_link = $this->site_logo_link();
    if (!$logo_link) $logo_link = 'helpstart.php';
    $smarty->assign(array('logo_link' => $logo_link,
                          'background_color' => $this->site_background_color(),
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
