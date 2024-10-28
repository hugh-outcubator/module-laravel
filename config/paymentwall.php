<?php

return [
    'base_url' => env('PAYMENTWALL_URL', 'https://api.paymentwall.com/api'),
    'one_time_token' => [
        'gateway_tokenization_url' => env('GATEWAY_TOKENIZATION_URL', 'https://pwgateway.com/api/token'),
    ],
    'public_key' => '6d3cc947b5e47342964c016f23047923',
    'private_key' => '38a675a47f1d34573fcead1ed290f3eb',
    'widget' => [
        'default_attributes' => [
            'frameborder' => '0',
            'width' => '750',
            'height' => '800'
        ]
    ],
    'ips_whitelist' => [
        '174.36.92.186',
        '174.36.96.66',
        '174.36.92.187',
        '174.36.92.192',
        '174.37.14.28'
    ],
    'range_whitelist' => [
        '216.127.71.0/24'
    ]
];