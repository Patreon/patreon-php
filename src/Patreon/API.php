<?php
namespace Patreon;

class API {
  private $access_token;

  public function __construct($access_token) {
    $this->access_token = $access_token;
  }

  public function fetch_user() {
    return $this->__get_json("current_user");
  }

  public function fetch_campaign_and_patrons() {
    return $this->__get_json("current_user/campaign");
  }

  private function __get_json($suffix) {
    $api_endpoint = sprintf("https://api.patreon.com/oauth2/api/%1$s", $suffix);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_GET, 1);
    $authorization_header = sprintf("Authorization: Bearer %1$s", $this->access_token);
    $headers = array($authorization_header);
    return json_decode(curl_exec($ch), true);
  }
}
