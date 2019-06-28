<?php

use BEAR\Accept\Accept;

require dirname(__DIR__) . '/autoload.php';

$available = [
    'Accept' => [
        'text/html' => 'html-app',
        'application/hal+json' => 'hal-api-app',
        'application/json' => 'api-app',
        'cli' => 'cli-html-app'
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
list($context) = $accept($_SERVER);
exit((require dirname(__DIR__) . '/bootstrap.php')($context));
