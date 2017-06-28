<?php

$composerAutoload = 'vendor/autoload.php';

require $composerAutoload;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;


// $clientId = $default['de_paypal_api_username'];
// $clientSecret = $default['de_paypal_api_password'];

$clientId = 'AdqV2Pnfu3Fl_-oXSpMXUDNTFk4vkqIoVppUf-KaEH5Hp39BgI86jC--9rkeJ_nOg8eBXU-1BRPnoIqv';
$clientSecret = 'EP_8ug9Demme4QIMQK2D5SbQChrPWbVuljXmWzvKFpJIX-utSI1Ew3JE-TgYJnnTZnNUqxw4XEQ3xrq2';

$apiContext = getApiContext($clientId, $clientSecret);

return $apiContext;

function getApiContext($clientId, $clientSecret)
{
    $apiContext = new ApiContext(
        new OAuthTokenCredential(
            $clientId,
            $clientSecret
        )
    );

    $apiContext->setConfig(
        array(
            'mode' => 'sandbox',
            'log.LogEnabled' => true,
            'log.FileName' => 'PayPal.log',
            'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'cache.enabled' => true,
            // 'http.CURLOPT_CONNECTTIMEOUT' => 30
            // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
            //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
        )
    );

    return $apiContext;
}


?>
