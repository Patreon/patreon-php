# patreon-php
Interact with the Patreon API via OAuth.

Get the plugin from [Packagist](https://packagist.org/packages/patreon/patreon)

Step 1. Get your client_id and client_secret
---
Visit the [OAuth Documentation Page](patreon.com/oauth2/documentation)
while logged in as a Patreon creator to register your client.

This will provide you with a `client_id` and a `client_secret`.

Step 2. Use this plugin
---
e.g., in a PHP page
```php
<?php

require_once('vendor/patreon/patreon/src/patreon.php');

use Patreon\API;
use Patreon\OAuth;

$client_id = null;      // Replace with your data
$client_secret = null;  // Replace with your data
$creator_id = null;     // Replace with your data

$oauth_client = new Patreon\OAuth($client_id, $client_secret);
$tokens = $oauth_client->get_tokens($_GET['code'], "http://localhost:5000/oauth/redirect");
$access_token = $tokens['access_token'];

$api_client = new Patreon\API($access_token);
$user_response = $api_client->fetch_user();
$user = $user_response['data'];
$included = $user_response['included'];
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

// use $user, $pledge, and $campaign as desired
```
