<?php
namespace Patreon;

require_once("JSONAPI/ResourceItem.php");
require_once("JSONAPI/ResourceIdentifier.php");
require_once("JSONAPI/Error.php");

class API {
  private $access_token;
  private $manager;

  public function __construct($access_token) {
    $this->access_token = $access_token;
    $factory = new \Art4\JsonApiClient\Utils\Factory([
        'ResourceItem' => 'Patreon\JSONAPI\ResourceItem',
        'ResourceIdentifier' => 'Patreon\JSONAPI\ResourceIdentifier',
        'Error' => 'Patreon\JSONAPI\Error'
    ]);
    $this->manager = new \Art4\JsonApiClient\Utils\Manager($factory);
  }

  public function fetch_user($parse = true) {
    return $this->__get_data("current_user", $parse);
  }

  public function fetch_campaign_and_patrons($parse = true) {
    return $this->__get_data("current_user/campaigns?include=rewards,creator,goals,pledges", $parse);
  }

  public function fetch_campaign($parse = true) {
    return $this->__get_data("current_user/campaigns?include=rewards,creator,goals", $parse);
  }

  public function fetch_page_of_pledges($campaign_id, $page_size, $cursor = null, $parse = true) {
    $url = "campaigns/{$campaign_id}/pledges?page%5Bcount%5D={$page_size}";
    if ($cursor != null) {
      $escaped_cursor = urlencode($cursor);
      $url = $url . "&page%5Bcursor%5D={$escaped_cursor}";
    }
    return $this->__get_data($url, $parse);
  }

  private function __get_data($suffix, $parse) {
      $ch = $this->__create_ch($suffix);
      $json_string = curl_exec($ch);
      $info = curl_getinfo($ch);
      if ($parse) {
          try {
              return $this->manager->parse($json_string);
          } catch ( Exception $e ) {
              return $json_string;
          }
      }
      // don't try to parse a 500-class error, as it's likely not JSON
      if ($info['http_code'] >= 500) {
          return $json_string;
      }
      return json_decode($json_string, true);
  }

  private function __create_ch($suffix) {
    $api_endpoint = "https://api.patreon.com/oauth2/api/" . $suffix;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Patreon-PHP, version 0.3.1, platform ".php_uname('s').'-'.php_uname('r'));
    $authorization_header = "Authorization: Bearer " . $this->access_token;
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($authorization_header));
    return $ch;
  }
}
