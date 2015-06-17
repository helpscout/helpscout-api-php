<?php

return [

    'getHappinessReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/happiness.json',
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
            'mailboxes' => [
                'location' => 'query'
            ],
            'tags' => [
                'location' => 'query'
            ],
            'types' => [
                'location' => 'query'
            ],
            'folders' => [
                'location' => 'query'
            ],
            'user' => [
                'location' => 'query',
                'required' => true
            ]
        ]
    ],

    'getHappinessRatingsReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/happiness/ratings.json',
        'parameters' => [
            'start' => [
                'location' => 'query',
                'required' => true
            ],
            'end' => [
                'location' => 'query',
                'required' => true
            ],
            'mailboxes' => [
                'location' => 'query'
            ],
            'tags' => [
                'location' => 'query'
            ],
            'types' => [
                'location' => 'query'
            ],
            'folders' => [
                'location' => 'query'
            ],
            'page' => [
                'location' => 'query',
                'required' => true
            ],
            'rating' => [
                'location' => 'query',
                'required' => true
            ],
            'sortField' => [
                'location' => 'query'
            ],
            'sortOrder' => [
                'location' => 'query'
            ]
        ]
    ]

];
