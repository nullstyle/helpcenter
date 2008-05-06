<?php

function this_or_that($left, $right) {
  return $left ? $left : $right;
}

class Settings {
  var $cached_row;
  
  function Settings() {

  }
  
  function get($name) {
    $this->load();
    return this_or_that($this->cached_row[$name], $GLOBALS[$name]);
  }
  
  function load() {
    if($this->cached_row) return;
    
    $sql =  'select contact_email, contact_phone, contact_address, '.
            'map_url, company_id, oauth_consumer_key, oauth_consumer_secret, '.
            'configured, logo_link, logo_data '.
            'from site_settings';
    $result = mysql_query($sql);
    
    if($result) $this->cached_row = mysql_fetch_array($result);
  }
}

?>