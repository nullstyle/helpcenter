<?

require_once('config.php');
require_once('Sprinkles.php');

$sprink = new Sprinkles();

$content = request_param('content');
$topic_id = request_param('topic_id');

$POST_URL = $topic_id. '/replies';

# print("posting to " . $POST_URL);

# FIXME: relying on reply being an extension of the post ID.
#$req = new HttpRequest($POST_URL,
#                       HTTP_METH_POST,
#                       array('httpauth' => 'ezra+sfn@ezrakilty.net:gicheeli',
#                             'httpauthtype' => HTTTP_AUTH_BASIC));
#$req->addPostFields(array('reply[content]' => $content));
#$resp = $req->send();

list($username, $token, $token_secret) = $sprink->current_user_creds();
$creds = array('token' => $token, 'token_secret' => $token_secret);
$req = $sprink->oauthed_request('POST', $POST_URL, $creds, null, 
                                array('reply[content]' => $content));
#                                array());

# FIXME: check for errors
print "<pre>";
print $req->getResponseBody();
print "</pre>";

redirect('topic.php?id=' . $topic_id);

?>
