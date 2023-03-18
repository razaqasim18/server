<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode' => env('PAYPAL_MODE', 'sandbox'),
    'sandbox' => [
        'username' => env('PAYPAL_SANDBOX_API_USERNAME', ''),
        'password' => env('PAYPAL_SANDBOX_API_PASSWORD', ''),
        'secret' => env('PAYPAL_SANDBOX_API_SECRET', ''),
        'certificate' => env('PAYPAL_SANDBOX_API_CERTIFICATE', ''),
        'app_id' => 'APP-80W284485P519543T',
    ],

    'live' => [
        'username' => env('PAYPAL_LIVE_API_USERNAME', ''),
        'password' => env('PAYPAL_LIVE_API_PASSWORD', ''),
        'secret' => env('PAYPAL_LIVE_API_SECRET', ''),
        'certificate' => env('PAYPAL_LIVE_API_CERTIFICATE', ''),
        'app_id' => '',
    ],

    'payment_action' => 'Sale',
    'currency' => env('PAYPAL_CURRENCY', 'USD'),
    'billing_type' => 'MerchantInitiatedBilling',
    'notify_url' => '',
    'locale' => '',
    'validate_ssl' => false,
];

// return [
//     'client_id' => 'AUS3oTaZtkrtZWde24yJ5JJsq1ja26LWmDJ7dSA8UtvMAtnFzi5C8IOrduzRhtYcejMrqdnJU9Z_eju4',
//     'secret' => 'ECIsZhk0I6vpntQYTqP_4pdPBn73mP2B11SY3jcjPHNzeI0Y3iEdia_WGDh6wGJ56jS0l_iZKNpjeArx',
//     'settings' => array(
//         'mode' => 'sandbox',
//         'http.ConnectionTimeOut' => 1000,
//         'log.LogEnabled' => true,
//         'log.FileName' => storage_path() . '/logs/paypal.log',
//         'log.LogLevel' => 'FINE',
//     ),
// ];
