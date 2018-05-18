<?php

/*
 If you're ever validating that a user on your site is a patron,
 you should *not* use this code.
 Instead, please use the proper OAuth flow,
 as detailed [in the documentation](https://www.patreon.com/platform/documentation/oauth)
 and in the snippet [in the README](https://www.github.com/Patreon/patreon-php/blob/master/README.md)

 If you want to just get a list of your patrons for non-authentication purposes,
 this snippet is a good example of how to do so.
 */

require_once('vendor/patreon/patreon/src/patreon.php');

use Patreon\API;
use Patreon\OAuth;

// Get your current "Creator's Access Token" from https://www.patreon.com/platform/documentation/clients
$access_token = null;
// Get your "Creator's Refesh Token" from https://www.patreon.com/platform/documentation/clients
$refresh_token = null;
$api_client = new API($access_token);

// Get your campaign data
$campaign_response = $api_client->fetch_campaign();

// If the token doesn't work, get a newer one
if ($campaign_response->has('errors')) {
    echo "Got an error\n";
    print_r($campaign_response->get('errors.0')->asArray());

    echo "Refreshing tokens\n";
    // Make an OAuth client
    // Get your Client ID and Secret from https://www.patreon.com/platform/documentation/clients
    $client_id = null;
    $client_secret = null;
    $oauth_client = new OAuth($client_id, $client_secret);
    // Get a fresher access token
    $tokens = $oauth_client->refresh_token($refresh_token, null);
    if ($tokens['access_token']) {
        $access_token = $tokens['access_token'];
        echo "Got a new access_token! Please overwrite the old one in this script with: " . $access_token . " and try again.";
    } else {
        echo "Can't fetch new tokens. Please debug, or write in to Patreon support.\n";
        print_r($tokens);
    }
    return;
}

if (!$campaign_response->has('data.0.id')) {
    echo "No campaign found. Please check you have an access token for a Patreon creator.\n";
}

// get page after page of pledge data
$campaign_id = $campaign_response->get('data.0.id');
$cursor = null;
while (true) {
    $pledges_response = $api_client->fetch_page_of_pledges($campaign_id, 25, $cursor);
    // loop over the pledges to get e.g. their amount and user name
    foreach ($pledges_response->get('data')->getKeys() as $pledge_data_key) {
        $pledge_data = $pledges_response->get('data')->get($pledge_data_key);
        $pledge_amount = $pledge_data->attribute('amount_cents');
        $patron = $pledge_data->relationship('patron')->resolve($pledges_response);
        $patron_full_name = $patron->attribute('full_name');
        echo $patron_full_name . " is pledging " . $pledge_amount . " cents.\n";
    }
    // get the link to the next page of pledges
    if (!$pledges_response->has('links.next')) {
        // if there's no next page, we're done!
        break;
    }
    $next_link = $pledges_response->get('links.next');
    // otherwise, parse out the cursor param
    $next_query_params = explode("?", $next_link)[1];
    parse_str($next_query_params, $parsed_next_query_params);
    $cursor = $parsed_next_query_params['page']['cursor'];
}

echo "Done!\n";

?>
