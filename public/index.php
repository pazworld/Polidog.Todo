<?php

use BEAR\Accept\Accept;

require dirname(__DIR__) . '/vendor/autoload.php';

$available = [
    'Accept' => [
        'text/html' => 'html-app',
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
list($context, $vary) = $accept($_SERVER);

require dirname(__DIR__) . '/bootstrap/bootstrap.php';
