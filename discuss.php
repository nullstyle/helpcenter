<?
require("class.myatomparser.php");
require("setup.php");
require_once("utils.php");

$h = new hkit;

###### GET COMPANY INFO ######

# TBD: pull this into utils.php
$company_url = $quick_mode ?
  $cache_dir.'companies-' . $company_id : 
  $api_root.'/companies/'.$companyid;
# print $company_url;
if ($quick_mode) {
  $company_hcard = $h->getByString('hcard', file_get_contents($company_url));
} else {
  $company_hcard = $h->getByURL('hcard',$company_url);
}

###### GET TOPICS ######
# dump($company_hcard);
$company_name = $company_hcard[0]["fn"];

if ($quick_mode) {
  $topics_feed_url = $cache_dir . 'companies-' . $company_id . '-topics.atom';
} else {
  $topics_feed_url = $api_root . "/companies/'.$company_id.'/topics";
}
$atom = new myAtomParser($topics_feed_url);
foreach ($atom->output as $feed) {
  $topics = $feed[""]["ENTRY"];        # FIXME extra level here.
  $topic_count = count($topics);  
  $topics = take($discuss_topic_count, $topics);
}

foreach ($topics as &$topic) {
  $topic["REPLY_COUNT"] = $topic["SFN:REPLY_COUNT"];
  if (!($topic["REPLY_COUNT"] > 0)) 
    $topic["REPLY_COUNT"] = 0;
}

###### GET COMPANY'S PEOPLE LIST ######
$company_people_url = $quick_mode ? 
    $cache_dir.'companies-' . $company_id . '-people' : 
    $api_root.'/companies/'.$company_id.'/people';
# print($company_people_url);
if ($quick_mode) {
  $company_people_list = $h->getByString('hcard',
                                         file_get_contents($company_people_url));
} else {
  $company_people_list = $h->getByURL('hcard',$company_people_url);
}
# dump($company_people_list);
###### FETCH THE PEOPLE RECORDS ######
$company_people = array();
foreach ($company_people_list as $person) {
  $person_record = $h->getByURL('hcard', $person["url"]);
#   $h->getByString('hcard',
#                   file_get_contents($cache_dir."people-40451.html"));
  array_push($company_people, $person_record[0]);
}

###### GET PRODUCT LIST ######
$company_products_url = $quick_mode ? 
    $cache_dir.'companies-' . 4 . '-products' : 
    $api_root.'/companies/'. 4 .'/products';
#print($company_products_url);

if ($quick_mode) {
  $company_products_list = $h->getByString('hproduct',
                                       file_get_contents($company_products_url));
} else {
  $company_products_list = $h->getByURL('hproduct',$company_products_url);
}
#dump($company_products_list);

###### FETCH THE PRODUCT RECORDS ######
$company_products = array();
foreach ($company_products_list as $product) {
  if (false && $quick_mode) {
    $product = $h->getByString('hproduct', file_get_contents($cache_dir.'/products-6681'));
  } else {
    $product = $h->getByURL('hproduct', $product["uri"]);
  }
  array_push($company_products, $product[0]);
}
#dump($company_products);
assert(is_array($company_products));

$smarty->assign('products', $company_products);
$smarty->assign('company_name', $company_name);
$smarty->assign('topics', $topics);
$smarty->assign('topic_count', $topic_count);
$smarty->display('discuss.t');
?>
