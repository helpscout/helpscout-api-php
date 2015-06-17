<?php

return [

    'getProductivityReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity.json',
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
            'officeHours' => [
                'location' => 'query'
            ]
        ]
    ],

    'getFirstResponseTimeProductivityReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity/first-response-time.json',
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
            'officeHours' => [
                'location' => 'query'
            ],
            'viewBy' => [
                'location' => 'query'
            ]
        ]
    ],

    'getRepliesSentProductivityReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity/replies-sent.json',
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

    'getResolvedProductivityReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity/resolved.json',
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

    'getResolutionTimeProductivityReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity/resolution-time.json',
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
            'officeHours' => [
                'location' => 'query'
            ],
            'viewBy' => [
                'location' => 'query'
            ]
        ]
    ],

    'getResponseTimeProductivityReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity/response-time.json',
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
            'officeHours' => [
                'location' => 'query'
            ],
            'viewBy' => [
                'location' => 'query'
            ]
        ]
    ],

    'getProductivityDrillDownReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity/drilldown.json',
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
