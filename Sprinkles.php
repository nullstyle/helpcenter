<?php
# $Id$

######################### Sprinkles.php ###############################
##
##  This file defines the core logic for interacting with the 
##  getsatisfaction.com web API, logic shared throughout the Sprinkles 
##  application, and general utility functions.
##
##  See individual methods and functions for detailed documentation.
##
##  NOTE: You can expect the interface to this file to change.
##
################

$page_timer = microtime(true);


$vendor_path = dirname(__FILE__) . '/vendor';
set_include_path(get_include_path() . PATH_SEPARATOR . $vendor_path);

set_exception_handler(default_exception_handler);
set_error_handler(default_error_handler);

require_once('HTTP_Request_Oauth.php');
require_once 'config.php';

# Smarty directory configuration
# We do this first so we can use it to handle errors.
require_once('smarty/Smarty.class.php');
$smarty = new Smarty();
$smarty->template_dir = $sprinkles_dir . '/themes/default/templates/';
$smarty->compile_dir  = $sprinkles_dir . '/templates_c';
$smarty->config_dir   = $sprinkles_dir . '/configs/';
$smarty->cache_dir    = $sprinkles_dir . '/cache/';

# Third-party libraries
require_once 'XML/Feed/Parser.php';
require_once 'hkit.class.php';

# Utilities
require_once 'list.php';

# The API library
require_once 'Satisfaction.php';

require_once 'Settings.php';

global $h;
$h = new hKit;

$mysql = mysql_connect($mysql_connect_params, $mysql_username, $mysql_password);
if (!$mysql) throw new Exception("Stopping: Couldn't connect to MySQL database.");

mysql_select_db($mysql_db ? $mysql_db : 'sprinkles');

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

class Sprinkles {

  var $company_sfnid;
  # Cache a few things here in case they're needed more than once in a request.
  var $employees_cache;
  var $people_cache = array();
  var $products_cache = array();
  var $settings;

  function Sprinkles() {
    $this->settings = new Settings();
    $this->company_sfnid = $this->settings->get('company_id');
    
  }

  function api_url($url) { return api_url($url); }
  
  function authorize_url($return, $first_login) {
    $consumer_data = $this->oauth_consumer_data();
    if (!$consumer_data['key'] || !$consumer_data['secret']){
      die("The OAuth consumer data was missing from the Instant-On Help " . 
          "Center database! Perhaps something went wrong during installation " . 
          "and setup.");
    }
    
    list($token, $secret) = get_oauth_request_token($consumer_data);
    
    if (!$token || !$secret) {
      error("Failed to fetch OAuth request token " . 
            "(Result token: '$token'; Token secret: '$token_secret')");
      die("Failed to fetch OAuth request token from getsatisfaction.com.");
    }
    
    $result = insert_into('sessions', array('token' => $token,
                                            'token_secret' => $secret));
    if (!$result) die("Error inserting OAuth tokens into database.");
    
    $callback_url = $this->sprinkles_root_url() . 'handle-oauth-return.php?' . 
                      ($first_login ? 'first_login=true&': '') .
                      'return=' . urlencode($return);

    return oauth_authorization_url($token, $callback_url);
  }

  ## Topics for the company, filtered by properites specified in $options.
  # Presently, you can only filter on one of the axes: product, tag, query, person,
  # followed, or related. The at_least parameter gives a minimum number of 
  # topics that should be returned. You might get more than this number. 
  # Using a smaller number should result in a quicker return.
  function topics($options) {
    return topics($this->company_sfnid, $options);
  }

  function resolve_authors(&$items) {
    resolve_authors($this->company_sfnid, $items);
  }

  function resolve_author(&$item) {
    resolve_author($this->company_sfnid, $item);
  }
  
  # resolve_companies adds company data to each item in a feed; it expects to
  # find a company_url and it populated the item with the vCard data found at 
  # that URL. This is done in place, on the array passed in. There is no return 
  # value.
  function resolve_companies(&$feed) {
    resolve_companies($feed);
  }

  # tags: return a list of tags as found at the given URL.
  function tags($url) {
    return tags($url);
  }

  # topic: fetch a single topic feed, converting the Atom feed to a record
  # (see fix_atom_entry for details).
  function topic($topic_id) {
    return topic($this->company_sfnid, $topic_id);
  }

  ## Get company info
  function company_hcard() {
    return company_hcard($this->company_sfnid);
  }


  function company_name() {
    return company_name($this->company_sfnid);
  }

  function employee_list() {
    return employee_list($this->company_sfnid);
  }

  ## Fetch the people records of all the company's people 
  function employees() {
    return employees($this->company_sfnid);
  }

  # Given a person URL, find their role at the Sprinkles company and return a
  # a pair of the technical role identifier (e.g. company_rep) and the human-
  # readable name of the role (e.g. "Official Rep"). Returns null if the given
  # person has no role at the current company.
  function get_person_role($person_url) {
    return get_person_role($this->company_sfnid, $person_url);
  }
  
  function get_person_from_string($str) {
    return get_person_from_string($str);
  }

  function get_person($url) {
    return get_person($url);
  }

  # Given a product URL or sfn:id, returns a structure describing the product.
  function get_product($url) {
    return get_product($url);
  }

  function product_list() {
    return product_list($this->company_sfnid);
  }

  # products: return full product information for each product connected with the
  # current company.
  function products() {
    return products($this->company_sfnid);
  }

  # Given a list of topics, partition it into the ones that are associated
  # with the current company and those that aren't.
  function company_partition($topics) {
    return company_partition($this->company_hcard(), $topics);
  }
  
  function set_company($company) {
    $sql = 'update site_settings set ' . 
           'company_id = \'' . $company . '\'';
    return mysql_query($sql);
  }

  function set_site_settings($settings) {
    $result = mysql_query('select count(*) from site_settings');
    $row = mysql_fetch_row($result);
    if ($row[0] < 1)
      mysql_query('insert into site_settings '.
                  'values (\'#86fff6\',null,null,null,null,null,null,' .
                  'null,null,null,null,null,null);');
    
    $sql = 'update site_settings set ';
    $i = 0;
    foreach ($settings as $name => $value) {
      if ($i++ > 0) $sql .= ', ';
      $sql .= $name . '=\'' . mysql_real_escape_string($value) . '\'';
    }

    return mysql_query($sql);
  }

  # add_admin_users adds the given list of usernames to the list of users that
  # have admin rights on this installation, leaving any current admins 
  # undisturbed. Does not signal any failure. Duplicate admin records may be 
  # created.
  function add_admin_users($admins) {
    foreach ($admins as $admin) {
      insert_into('admins', array('username' => $admin));
    }
  }

  # set_admin_users sets the list of users that have admin rights on this
  # Sprinkles installation to the given list of usernames, clearing out any 
  # that alread have admin rights. Does not signal any failure.
  function set_admin_users($admins) {
    if (!mysql_query('delete from admins'))
      throw new Exception("Database error deleting from table admins: " . mysql_error());
    foreach ($admins as $admin) {
      insert_into('admins', array('username' => $admin));
    }
  }
  
  var $nascent_session_id;   # the session ID, when we've just set it and have not yet received a cookie
  
  function open_session($token) {
    if (!$token) throw new Exception("Call to open_session with blank token: '$token'");
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
  # a request using HTTP method $method to the url $url, supplying OAuth
  # credentials from $cred and using the optional request parameters
  # $req_params (as expected by the Http_Request class) and HTTP query 
  # parameters $query_params. The Oauth credentials should be an associative 
  # array having keys 'token' and 'token_secret', containing the corresponding
  # Oauth values. The OAuth consumer_key and consumer_secret are those given
  # when Sprinkles was installed.
  # If the OAuth server indicates that we are unauthorized (401 Unauthorized),
  # we close the session so that the user will be asked to re-authorize on
  # the next action.
  function oauthed_request($method, $url, $creds, $req_params, $query_params) {
    $req = oauthed_request($this->oauth_consumer_data(), $method, $url,
                           $creds, $req_params, $query_params);
    if ($req->getResponseCode() == 401) {
      debug("Got response from API: " . $req->getResponseBody());
      # The session was no good; close it so the user can create a new one on next login
      $this->close_session($creds['token']);
      throw new Exception("$method request for $url failed to authorize.");
    }
    return $req; 
  }
 
  # get_me_person fetches the path /me from the API, using the given Oauth
  # credentials, and that resource contains a vCard for the user with those
  # credentials.
  function get_me_person($creds) {
    try {
      return get_me_person($this->oauth_consumer_data(), $creds);      
    } catch(Exception $e) {
      $this->close_session();
      return null;
    }
  }

  function current_user_session() {
    $session_id = $this->nascent_session_id;
    if (!$session_id) $session_id = $_COOKIE["session_id"];
    if (!$session_id) return null;
    $sql = "select token,token_secret, username, user_url, user_photo, user_fn " .
           "from sessions where token='". $session_id . "'";
    $result = mysql_query($sql);
    if (!$result) { throw new Exception("Database error looking up current session: "
                                        . mysql_error()); }
    $cols = mysql_fetch_array($result);

    if (!$cols) {  # Cookie session was not in DB; clear it.
      setcookie('session_id', ''); return null;
    }
    return array('token' => $cols[0],
                 'token_secret' => $cols[1],
                 'username' => $cols[2],
                 'user_url' => $cols[3],
                 'user_photo' => $cols[4],
                 'user_fn' => $cols[5]);
  }
  
  # current_user returns the vCard of the currently logged-in user. If it needs to
  # fetch the user's vCard, it will store it in the database.
  # The returned vCard also contains a boolean field 'sprinkles_admin' indicating
  # whether this user has the privilege to administer this Sprinkles installation.
  function current_user() {
    $session = $this->current_user_session();
    if (!$session || !$session['token'] || !$session['token_secret']) return null;
    $username = $session['username'];
    if (!$username) {
      # We don't have any user data for the current user, we need to fetch it.
      $me_person = $this->get_me_person($session);
	  if (!$me_person) return null;
	  $username = $me_person['canonical_name'];
	  if (!$username) throw new Exception("Current user had no canonical_name");
	  $query = mysql_query("select * from admins where username = '" .
                           $me_person['canonical_name'] . "'");
      if ($row = mysql_fetch_array($query))
        $me_person['sprinkles_admin'] = true;
      $sql = "update sessions set" . 
	         " username = '" . $username . "'," . 
	         " user_url = '" . $me_person['url'] . "'," . 
	         " user_photo = '" . $me_person['photo'] . "'," . 
	         " user_fn = '" . $me_person['fn'] . "'," . 
	         " user_sprinkles_admin = '" . ($me_person['sprinkles_admin'] ? 'Y' : 'N') . "'" . 
	  		 " where token = '" . $session['token'] . "'";
	  $result = mysql_query($sql);
	  if (!$result) throw new Exception("Failed to cache current user data in database: " . mysql_error());
    } else {
      $me_person = array('canonical_name' => $session['username'],
                         'fn' => $session['user_fn'],
                         'url' => $session['user_url'],
                         'photo' => $session['user_photo'],
                         'sprinkles_admin' => $session['user_sprinkles_admin'],
                         );
    }

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
    return ($this->settings->get('configured') == 'Y');
  }

  function site_contact_info() {
    return array('contact_email' => $this->settings->get('contact_email'),
                 'contact_phone' => $this->settings->get('contact_phone'),
                 'contact_address' => $this->settings->get('contact_address'), 
                 'map_url' => $this->settings->get('map_url'));
  }
  
  function oauth_consumer_data() {
    return array(
      'key' => $this->settings->get('oauth_consumer_key'), 
      'secret' => $this->settings->get('oauth_consumer_secret')
    );
  }
  
  function findsite_data() {
    $oauth_consumer_data = $this->oauth_consumer_data();
    $sprinkles_root_url = $this->sprinkles_root_url();
    return array('oauth_consumer_key' => $oauth_consumer_data['key'],
                 'oauth_consumer_secret' => $oauth_consumer_data['secret'],
                 'company_sfnid' => $this->company_sfnid,
                 'sprinkles_root_url' => $sprinkles_root_url
                 );
  }

  function site_links() {
    $query = mysql_query('select text, url from site_links');
    $result = array();
    while ($row = mysql_fetch_row($query))
      array_push($result, array('text' => $row[0], 'url' => $row[1]));
    return $result;
  }

  function set_site_links($links) {
    mysql_query('delete from site_links');
    foreach ($links as $link) 
      insert_into('site_links', array('text' => $link['text'],
                                      'url' => $link['url']));
  }
  
  function site_logo() {
    return $this->settings->get('logo_data');
  }

  function site_logo_link() {
    return $this->settings->get('logo_link');
  }
  
  # FIXME: get the whole site_settings row ONCE per request.
  function sprinkles_root_url() {
    $q = mysql_query('select sprinkles_root_url from site_settings');
    if (!$q) throw new Exception("Database error getting Sprinkles root URL: " . mysql_error());
    $row = mysql_fetch_row($q);
    return $row[0];
  }

  # add_std_hash_elems takes a Smarty object and sets some common variables 
  # that are used on every page.
  function add_std_hash_elems($smarty) {
    $current_user = $this->current_user();
    $logo_link = $this->site_logo_link();
    if (!$logo_link) $logo_link = 'helpstart.php';
    global $page_timer;
    
    $smarty->assign(array('logo_link' => $logo_link,
                          'background_color' => $this->site_background_color(),
                          'company_name' => $this->company_name(),
                          'contact_address' => $this->settings->get('contact_address'),
                          'contact_email' => $this->settings->get('contact_email'),
                          'contact_phone' => $this->settings->get('contact_phone'),
                          'current_user' => $current_user,
                          'user_name' => $current_user['fn'],
                          'site_configured' => $this->site_configured(),
                          'sprinkles_root_url' => $this->sprinkles_root_url(),
                          'user_is_admin' => $this->user_is_admin(),
                          'page_timer' => microtime(true) - $page_timer));
  }
}

function redirect($url) {
  header('Location: ' . $url, true, 302);
}

function finish_request($page) {
  global $page_timer;
  message("Page $page rendered in " . (microtime(true) - $page_timer));
}

function default_exception_handler($exc) {
  redirect('error.php?msg=' . urlescape($exc->getMessage()));
}

function default_error_handler($errno, $errstr, $errfile, $errline, $errcontext) {
   if ($errno != E_NOTICE && $errno != E_STRICT) {
     error("PHP Error (level $errno): '$errstr' at $errfile, line $errline, $errcontext.");
   }
   if ($errno != E_WARNING && $errno != E_NOTICE && $errno != E_STRICT &&
       $errno != E_CORE_WARNING && $errno != E_COMPILE_WARNING &&
       $errno != E_USER_WARNING && $errno != E_USER_NOTICE) {
     die();
   }
}

?>