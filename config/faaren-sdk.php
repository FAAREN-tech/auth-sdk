<?php

return [
    'service_url' => env('FAAREN_AUTH_SERVICE_URL', 'http://faaren.test:8008/service/api'),

    'notification_service' => [
        'endpoints' => [
            'production' => env('NS_ENDPOINT_PRODUCTION', 'https://services.faaren.com/notification/'),
            'staging' => env('NS_ENDPOINT_STAGING', 'https://services.staging.faaren.com/notification/'),
            'dev' => env('NS_ENDPOINT_DEV', 'notification:8080/')
        ]
    ]
];
