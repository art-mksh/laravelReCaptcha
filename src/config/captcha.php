<?php

return [
    'http_request_options' => [
        'timeout' => env('RECAPTCHA_HTTP_TIMEOUT', 30),
    ],
    'request_retry_count' => env('RECAPTCHA_TRY_COUNT', 2),
    'secretkey' => env('RECAPTCHA_SECRET')
];
