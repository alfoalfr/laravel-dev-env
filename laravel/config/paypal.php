<?php
return array(
    'client_id' => env('PAYPAL_KEY'),
    'secret' => env('PAYPAL_SECRET'),

    'settings' => [
        //Available option 'sandbox' or 'live'
        'mode' => 'sandbox',
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        //Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
        'log.LogLevel' => 'FINE'
    ],
);