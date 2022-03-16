<?php

return [
    'service_url' => env('FAAREN_AUTH_SERVICE_URL', 'http://faaren.test:8008/service/api'),

    'notification_service' => [
        'endpoints' => [
            'production' => env('NS_ENDPOINT_PRODUCTION', 'https://services.faaren.com/notification/'),
            'staging' => env('NS_ENDPOINT_STAGING', 'https://services.staging.faaren.com/notification/'),
            'dev' => env('NS_ENDPOINT_DEV', 'notification:8080/')
        ],

        'tokens' => [
            'production' => env('NS_TOKEN_PRODUCTION', 'nq6FjyM8HkUYX59mCbuwFwnDgduw54pJMTRdY8MxjC5UkNhshRK5WTDv6kxdsnRX'),
            'staging' => env('NS_TOKEN_STAGING', 'zVHGJsQM4XL9CN8WqftvC6YWx2kgsbEnTeeXJrAUV77YgLFaXaWSwtV2W3Bn387y'),
            'dev' => env('NS_TOKEN_DEV', 'ZQMQqW9DaS4Gs9wpcSEQ5xjm7nYaQCb9K6dYwjTMFkrJwRww4C2BV28TCH26fMCk')
        ]
    ]
];
