# 1.0.0

* Increased minimum PHP version to 7.0 
* Library moved to use Patreon API v2 endpoints and calls
* Scopes added to examples and API url functions
* Autoloading has been moved to PSR4
* Art4 JSON parser library removed
* API class set to provide returns from the API in JSON, array, or object form - choice to parse in other ways (like using Art4 lib is left to the developer - ie, get JSON return and then feed to Art4 lib which can be installed separately)
* Caching of unique calls to API implemented in API class in order to speed up any repeated calls and reduce load on the API
* Library directory structure simplified
* Unnecessary includes and unnecessary files removed
* login_with_patreon.png and unlock_with_patreon.png images are added under assets/images for developers to use with their apps
* Unified flow example added to provide an example for content unlocking by having users login/register/pledge at Patreon and return to the app in just one smooth flow
* Webhook write example added - creates a webhook to notify your local app when there are any membership changes in your campaign
* Examples made more detailed and comprehensive
* Readme example reformatted, updated to API V2
* Add unit testing (via phpunit)
* Add static analysis (via Psalm)
* **Security:** Force server-side HTTP request to use TLS (and check certificates)
  * This explicitly chooses the most secure options available
  * Future effort: Migrate to Guzzle instead of using cURL?
* Throw exceptions if cURL fails
* **Performance and Security:** Use BLAKE2b instead of MD5 for request caching
  * Prevents accidental collisions, which is a real risk since the
    effective cost of attacking MD5 is 2^18
  * Only if ext/sodium is installed (always should be for PHP 7.2+)
* Security: Internally, use HiddenString objects to encapsulate API tokens and OAuth credentials
  * This helps hide sensitive data from stack traces
  * This keeps users' client secrets and access tokens out of tickets

# 0.3.2

* Art4 JSON lib removed in preparation for API v2 upgrade
* API class now allows JSON, array, object formatted returns from the API
* PSR4 autoloading
* Directory structure simplified
* Extra patreon.php file removed
* Example in readme updated to reflect the changes
* Unnecessary extra includes removed

## Fixes

# 0.3.1

## Fixes

* Cleanup on doc and examples

# 0.3.0

* Improve documentation in the README
* Fix snippet samples
* Add User-Agent string for correct platform attribution
