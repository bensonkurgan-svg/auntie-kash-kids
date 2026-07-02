<?php
return [
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],
    'team' => [
        'notification_email' => env('TEAM_NOTIFICATION_EMAIL', 'hello@auntiekashkids.com'),
    ],
];
