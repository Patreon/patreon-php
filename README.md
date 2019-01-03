# patreon-php
Interact with the Patreon API via OAuth. (This is a development package that will be removed later. Please do not use this)

## Installation

Get the plugin from [Packagist](https://packagist.org/packages/patreon/patreon)

## Usage
### Step 1. Get your client_id and client_secret
Visit the [Patreon platform documentation page](https://www.patreon.com/platform/documentation)
while logged in as a Patreon creator to register your client.

This will provide you with a `client_id` and a `client_secret`.

### Step 2. Use this plugin in your code
Let's say you wanted to make a "Log In with Patreon" button.
You've read through [the directions](https://www.patreon.com/platform/documentation/oauth),
and are trying to implement "Step 2: Handling the OAuth Redirect" with your server.
The user will be arriving at one of your pages *after* you have sent them to the authorize page (at https://www.patreon.com/oauth2/authorize) for step 1,
so in their query parameters landing on this page,
they will have a parameter `'code'`.

_(If you are doing something other than the "Log In with Patreon" flow, please see [the examples folder](examples) for more examples)_

```php
<?php
 
/*
 * Use the following,
 * or if you installed via Composer, it should already be required via autoloader
 */

require_once __DIR__.'/vendor/autoload.php';
 
use Codebard\API;
use Codebard\OAuth;

$client_id = '';      // Replace with your data
$client_secret = '';  // Replace with your data

$oauth_client = new OAuth($client_id, $client_secret);

// There will a simple login link generator from a new class here - instead of the makeshift code below

$redirect_uri = "http://pat-php-dev.codebard.com";

$href = 'https://www.patreon.com/oauth2/authorize?response_type=code&client_id=' 
. $client_id . '&redirect_uri=' . urlencode($redirect_uri);

echo '<a href="'.$href.'">Click here to login via Patreon</a>';
echo '<br>';

if ( $_GET['code'] != '' ) {
		
	$tokens = $oauth_client->get_tokens($_GET['code'], $redirect_uri);
	$access_token = $tokens['access_token'];
	$refresh_token = $tokens['refresh_token'];
	
	// There will be some advice for devs on how to save their tokens and match it to their users here

	$api_client = new API($access_token);
	$patron_response = $api_client->fetch_user();
	
	echo '<pre>';
	print_r($patron_response);
	echo '</pre>';
	
	
}


?>
```
