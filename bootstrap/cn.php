<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$available = [
    'Accept' => [
        'text/html' => 'html-app',
        'application/hal+json' => 'hal-api-app',
        'application/json' => 'api-app',
        'cli' => 'cli-hal-api-app'
    ],
    'Accept-Language' => [
        'ja' => 'ja',
        'en-US' => 'en'
    ]
];
$accept = new \BEAR\Accept\Accept($available);
list($context, $vary) = $accept($_SERVER);

require __DIR__ . '/bootstrap.php';
