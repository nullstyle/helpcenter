<?

require_once('config.php');
require_once('Sprinkles.php');

$subject = request_param('subject');
$details = request_param('details');
$tags = request_param('tags');
$face = request_param('emoticon');

$req = new HttpRequest('http://api.getsatisfaction.com/topics',
                       HTTP_METH_POST,
                       array('httpauth' => 'ezra+sfn@ezrakilty.net:gicheeli',
                             'httpauthtype' => HTTTP_AUTH_BASIC));
$req->addPostFields(array('topic[company_domain]' => 'sprinklestestcompany',
                          'topic[subject]' => $subject,
                          'topic[additional_detail]' => $details,
                          'topic[keywords]' => $tags
                          #, 'topic[emoticon][face]' => $face
));
$resp = $req->send();

# TBD: On a 401, expire the token.

#print "<pre>";
#print $resp->getBody();
#print "</pre>";

$topic_feed = new XML_Feed_Parser($resp->getBody());

if ($topic_feed->id()) {     # FIXME: better error checking here.
  redirect('topic.php?id=' . $topic_feed->id());
} else {
  print "An error occured";
}

?>
