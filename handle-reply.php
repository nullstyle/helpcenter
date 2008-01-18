<?

require_once('config.php');
require_once('Sprinkles.php');

$content = request_param('content');
$topic_id = request_param('topic_id');

$POST_URL = $topic_id. '/replies';

# print("posting to " . $POST_URL);

# FIXME: relying on reply being an extension of the post ID.
$req = new HttpRequest($POST_URL,
                       HTTP_METH_POST,
                       array('httpauth' => 'ezra+sfn@ezrakilty.net:gicheeli',
                             'httpauthtype' => HTTTP_AUTH_BASIC));
$req->addPostFields(array('reply[content]' => $content));
$resp = $req->send();

# FIXME: check for errors
#print "<pre>";
#print $resp->getBody();
#print "</pre>";

redirect('topic.php?id=' . $topic_id);

?>