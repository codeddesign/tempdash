<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Business Configuration
    |--------------------------------------------------------------------------
    |
    | Contains various configurations specific to business logic.
    |
    */

    'types_of_companies' => [
        'advertiser' => 'Advertiser',
        'agency' => 'Agency',
        'ssp' => 'SSP',
        'exchange' => 'Exchange',
        'dsp' => 'DSP',
        'ad_network' => 'Ad Network',
        'publisher' => 'Publisher'
    ],
    'do_two_factor' => env('DO_TWO_FACTOR_AUTH', false),
    'token_life' => 10,
    'activity_count_limit' => 10,
    'verify_email_sender' => 'verify@ternio.io',
    'business_name' => 'Ternio',
    'tagline' => 'Programmatic Advertising Blockchain'
];