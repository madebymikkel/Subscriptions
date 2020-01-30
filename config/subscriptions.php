<?php

return [

    'key'      => env('STRIPE_KEY'),
    'secret'   => env('STRIPE_SECRET'),
    'currency' => env('SUBSCRIPTION_CURRENCY', 'usd'),
];
