<?php

use BEAR\Accept\Accept;

require dirname(__DIR__) . '/vendor/autoload.php';

$available = [
    'Accept' => [
        'application/hal+json' => 'hal-api-app',
        'application/json' => 'api-app',
        'cli' => 'cli-hal-api-app'
    ],
    'Accept-Language' => [
        'ja' => 'ja',
        'ja-JP' => 'ja',
        'en' => 'en',
        'en-US' => 'en',
        'en-GB' => 'en'
    ]
];
$accept = new Accept($available);
list($context, $vary) = $accept($_SERVER);

require dirname(__DIR__) . '/bootstrap/bootstrap.php';
