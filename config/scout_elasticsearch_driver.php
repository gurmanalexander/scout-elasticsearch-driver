<?php

return [
    'client' => [
        'hosts' => [
            env('SCOUT_ELASTIC_HOST', 'localhost:9200')
        ]
    ],
    'engine' => [
        'force_document_refresh' => env('SCOUT_ELASTIC_FORCE_DOCUMENT_REFRESH', false)
    ]
];
