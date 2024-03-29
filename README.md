# php-ripple-lib
PHP API for interacting with the XRP Ledger

Upgrade guzzle to version 7.*

Min php supported version 7.2

## Installation
`composer require lessmore92/php-ripple-lib`


## Sample Usage
```php
require_once "vendor/autoload.php";

$options = [
    'server'  => 'https://s.altnet.rippletest.net:51234',
    'timeOut' => 5,
];

$api    = new \Lessmore92\Ripple\RippleAPI($options);
$server = $api->serverInfo();
var_dump($server);

$address = 'rEdhDpVvmryJcMNcFTzXW3hAmiCkUXUru6';
$balance = $api->accountInfo($address)->balance->toXrp();
var_dump($balance);

$txs = $api->accountTx($address, []);
var_dump($txs);

$fee = $api->getFee();
var_dump($fee);

```
