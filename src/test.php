<?php

use Patreon\OAuth;
use Patreon\API;

$oauth_client = OAuth(client_id, client_secret);
$tokens = $oauth_client.get_tokens("TK4H673Bi8mmY0q4aQF7tHqbfPYVhQ", "http://localhost:5000/oauth/redirect");
$access_token = $tokens['access_token'];

$api_client = API($access_token);
$user_response = $api_client.fetch_user();
print_r($user_response);
$user = $user_response['data'];
$included = $user_response.get('included');
$creator_id = "32187";
$pledge = null;
$campaign = null;
if ($included != null) {
  foreach ($included as $obj) {
    if ($obj["type"] == "pledge" && $obj["relationships"]["creator"]["data"]["id"] == $creator_id) {
      $pledge = $obj;
      break;
    }
  }
  foreach ($included as $obj) {
    if ($obj["type"] == "campaign" && $obj["relationships"]["creator"]["data"]["id"] == $creator_id) {
      $campaign = $obj;
      break;
    }
  }
}
print_r($user);
print_r($pledge);
print_r($campaign);