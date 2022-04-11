<?php

return [

    'payment' => [
        'clientId' => env('PAYMENT_CLIENT_ID'),
        'clientSecret' => env('PAYMENT_CLIENT_SECRET'),
        'url' => env("PAYMENT_SERVICE_URL")
    ],

];
