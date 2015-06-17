<?php

return [

    'getTeamReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/team.json',
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
            ]
        ]
    ],

    'getCustomersHelpedTeamReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/team/customers-helped.json',
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
            'viewBy' => [
                'location' => 'query'
            ]
        ]
    ],

    'getTeamDrillDownReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/team/drilldown.json',
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
                'location' => 'query'
            ],
            'rows' => [
                'location' => 'query'
            ],
            'range' => [
                'location' => 'query',
                'required' => true
            ],
            'rangeId' => [
                'location' => 'query'
            ]
        ]
    ]

];
