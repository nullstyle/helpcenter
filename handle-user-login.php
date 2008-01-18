<?

require_once('config.php');
require_once('Sprinkles.php');
require_once('HTTP_Request_Oauth.php');

$oauth_req = new HTTP_Request_OAuth(
                   'http://getsatisfaction.com/api/request_token',
                   array('consumer_key' => 'lmwjv4kzwi27',
                         'consumer_secret' => 'fiei6iv61jnoukaq1aylwd8vcmnkafrs',
                         'signature_method' => 'HMAC-SHA1',
                         'method' => 'GET'));

$resp = $oauth_req->sendRequest(true, true);
dump($oauth_req->getResponseBody());
list($token, $secret) = $oauth_req->getResponseTokenSecret();
#dump(array($token, $secret));
$result = mysql_query('insert into oauth_tokens (token, token_secret) values (\''
                      . $token . '\', \'' . $secret . '\')');

if (!$result) die("Error inserting OAuth tokens into database.");

$sprinkles_root_url = 'http://localhost/sprinkles';

print('would redirect to http://getsatisfaction.com/api/authorize?oauth_token='
      . $token . '&oauth_callback=' . $sprinkles_root_url . '<br />');
die;

#FIXME hardcoded API URL
redirect('http://getsatisfaction.com/api/authorize?oauth_token=' . $token . 
         '&oauth_callback=' . $sprinkles_root_url);

$sprink = new Sprinkles($company_id);

$email = request_param('email');
$password = request_param('email');

$sprink->open_session($email. ':' . $password);

$return = request_param('return');

redirect($return);

?>