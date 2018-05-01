# patreon-php
Interact with the Patreon API via OAuth.

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
The user will be arriving at one of your pages *after* you have sent them to [the authorize page](www.patreon.com/oauth2/authorize) for step 1,
so in their query parameters landing on this page,
they will have a parameter `'code'`.

_(If you are doing something other than the "Log In with Patreon" flow, please see [the examples folder](examples) for more examples)_

```php
<?php

/*
 * Use the following,
 * or if you installed via Composer, it should already be required via autoloader
 */
require_once('vendor/patreon/patreon/src/patreon.php');

use Patreon\API;
use Patreon\OAuth;

$client_id = null;      // Replace with your data
$client_secret = null;  // Replace with your data

$oauth_client = new OAuth($client_id, $client_secret);

// Replace http://localhost:5000/oauth/redirect with your own uri
$redirect_uri = "http://localhost:5000/oauth/redirect";
/*
 * Make sure that you're using this snippet as Step 2 of the OAuth flow: https://www.patreon.com/platform/documentation/oauth
 * so that you have the 'code' query parameter.
 */
$tokens = $oauth_client->get_tokens($_GET['code'], $redirect_uri);
$access_token = $tokens['access_token'];
$refresh_token = $tokens['refresh_token'];

$api_client = new API($access_token);
$patron_response = $api_client->fetch_user();
/*
 * The $patron_response is now a [art4/json-api-client/Document](https://github.com/Art4/json-api-client/blob/master/docs/objects-document.md)
 * You can access its primary data via ->get('data')
 */
$patron = $patron_response->get('data');
// Can now check attributes, e.g. $patron->attribute('full_name');
$pledge = null;
/*
 * Given a resource in the document, you can access its relationships with ->relationship->($relationship_name)
 * or check for a relationship's existence with ->has('relationships.relationship_name')
 */
if ($user->has('relationships.pledges')) {
    /*
     * To look up the full resource that the relationship is referencing,
     * we have extended [art4/json-api-client/ResourceIdentifier](https://github.com/Art4/json-api-client/blob/master/docs/objects-resource-identifier.md)
     * with the `->resolve` method.
     * You pass in the original response document, and you get back a full resource,
     * with attributes, relationships, etc.
     */
    $pledge = $user->relationship('pledges')->get(0)->resolve($user_response);
    // Can now check attributes, e.g. $pledge->attribute('amount_cents');
}

/*
 $patron will have the authenticated user's user data, and
 $pledge will have their patronage data.
 Typically, you will save the relevant pieces of this data to your database,
 linked with their user account on your site,
 so your site can customize its experience based on their Patreon data.
 You will also want to save their $access_token and $refresh_token to your database,
 linked to their user account on your site,
 so that you can refresh their Patreon data on your own schedule.
 */

?>
```
