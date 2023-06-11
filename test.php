<pre>
<?php
/**
 * User: Lessmore92
 * Date: 6/11/2023
 * Time: 8:01 PM
 */
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
