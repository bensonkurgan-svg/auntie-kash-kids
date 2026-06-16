<?php
return [
    'default' => env('MAIL_MAILER', 'resend'),
    'mailers' => [
        'resend' => ['transport' => 'resend'],
        'log' => ['transport' => 'log', 'channel' => env('MAIL_LOG_CHANNEL')],
        'array' => ['transport' => 'array'],
    ],
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@auntiekashkids.com'),
        'name' => env('MAIL_FROM_NAME', 'Auntie Kash Kids Academy'),
    ],
];
