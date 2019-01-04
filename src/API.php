<?php
namespace codebard;

class API {
  private $access_token;
  private $manager;

  public function __construct($access_token) {
    $this->access_token = $access_token;
  }

  public function fetch_user() {
    return $this->get_data("current_user", $parse);
  }

  public function fetch_campaign_and_patrons() {
    return $this->get_data("current_user/campaigns?include=rewards,creator,goals,pledges", $parse);
  }

  public function fetch_campaign() {
    return $this->get_data("current_user/campaigns?include=rewards,creator,goals", $parse);
  }

  public function fetch_page_of_pledges($campaign_id, $page_size, $cursor = null) {
    $url = "campaigns/{$campaign_id}/pledges?page%5Bcount%5D={$page_size}";
    if ($cursor != null) {
      $escaped_cursor = urlencode($cursor);
      $url = $url . "&page%5Bcursor%5D={$escaped_cursor}";
    }
    return $this->get_data($url, $parse);
  }

  public function get_data($suffix, $format = 'array') {
	  
	  // Caching logic or var will go in here
	  
      $ch = $this->__create_ch($suffix);
      $json_string = curl_exec($ch);
      $info = curl_getinfo($ch);
	  
      // don't try to parse a 500-class error, as it's likely not JSON
      if ( $info['http_code'] >= 500 ) {
          return $json_string;
      }
	  
	  if( $format == 'array' ) {
		  $return = json_decode($json_string, true);
	  }
	  if( $format == 'object' ) {
		  $return = json_decode($json_string);
	  }
	  if( $format == 'json' ) {
		  $return = $json_string;
	  }
	  
      return $return;
  }

  private function __create_ch($suffix) {
    $api_endpoint = "https://api.patreon.com/oauth2/api/" . $suffix;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $authorization_header = "Authorization: Bearer " . $this->access_token;
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($authorization_header));
    return $ch;
  }
}