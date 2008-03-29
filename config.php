<?php

# Directory where the Sprinkles code, and the caches, reside.
$sprinkles_dir = dirname(__FILE__);

# API root URL and caching directory
$api_root = 'http://api.getsatisfaction.com/';

$mysql_username = '';
$mysql_password = '';

# Configure this if you need to talk to a MySQL database on another machine
# or use a nonstandard port. The default should normally work. 
# (FIXME: this doesn't apparently do anything.)
$mysql_connect_params = 'localhost:3306';

$max_logo_size = 65535;

# page limits

$submit_suggestions = 3;
$helpstart_topic_count = 5; 
$discuss_page_size = 6;
$related_topics_count = 4;
$max_top_topic_tags = 5;
$topic_page_size = 5; # TBD: regularize these names


# $preview_after_log determines whether the user should have a chance to 
# preview a topic for submission, after being sent to the Get Satisfaction 
# authorization page and before actually posting the topic.

$preview_after_login = true;

?>