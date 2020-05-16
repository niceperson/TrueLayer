# Truelayer Helper
requesting Truelayer API made simple

# Usage
```php
use Niceperson\Truelayer\Authorization;
use Niceperson\Truelayer\Credentials;
use Niceperson\Truelayer\Request;
use Niceperson\Truelayer\Data;

// best if taken from .env below's example is to make life easier
defined('TRUELAYER_CLIENT_ID') or define('TRUELAYER_CLIENT_ID', 'xxxx');
defined('TRUELAYER_CLIENT_SECRET') or define('TRUELAYER_CLIENT_SECRET', 'yyyyy');
defined('TRUELAYER_REDIRECT_URI') or define('TRUELAYER_REDIRECT_URI', 'https://localhost:3000/callback');
defined('TRUELAYER_USE_SANDBOX') or define('TRUELAYER_USE_SANDBOX', 'true');


$credentials = new Credentials(
    TRUELAYER_CLIENT_ID,
    TRUELAYER_CLIENT_SECRET,
    TRUELAYER_REDIRECT_URI,
    TRUELAYER_USE_SANDBOX,  // this will add mock provider wehn generation auth link
);

$truelayer_request = new Request(['timeout' => 60]); // options as per Guzzle client
$auth = new Authorization($truelayer_request, $credentials, TRUELAYER_USE_SANDBOX);


// to generate auth link
$auth_link = $auth->getAuthLink();

// to exchange code
$token = $auth->getAccessToken($code); // $code given by truelayer

// to perform data request using token.
$data = new Data($truelayer_request, $token, TRUELAYER_USE_SANDBOX);

//------------------------------------------------------------| data requests

$result = $data->fetch('META_ME');
$result = $data->fetch('ACCT_LIST');
$result = $data->fetch('CARD_LIST');
$result = $data->fetch('ACCT_LIST');
$result = $data->fetch('CARD_LIST');
$result = $data->fetch('ACCT_VIEW', $account_id);
$result = $data->fetch('CARD_VIEW', $account_id);
$result = $data->fetch('ACCT_BALANCE', $account_id);
$result = $data->fetch('CARD_BALANCE', $account_id);
$result = $data->fetch('ACCT_TRANSACTIONS', $account_id);
$result = $data->fetch('CARD_TRANSACTIONS', $account_id);
$result = $data->fetch('ACCT_TRANSACTIONS_PENDING', $account_id);
$result = $data->fetch('CARD_TRANSACTIONS_PENDING', $account_id);
$result = $data->fetch('ACCT_DIRECT_DEBITS', $account_id);
$result = $data->fetch('ACCT_STANDING_ORDER', $account_id);
```