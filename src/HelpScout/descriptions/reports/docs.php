<?php

return [

    'getDocsReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/docs.json',
        'parameters' => [
            'start' => [
                'location' => 'query',
                'required' => true
            ],
            'end' => [
                'location' => 'query',
                'required' => true
            ],
            'previousStart' => [
                'location' => 'query'
            ],
            'previousEnd' => [
                'location' => 'query'
            ],
            'sites' => [
                'location' => 'query'
            ]
        ]
    ]

];
