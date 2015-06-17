<?php

return [

    'getConversationsReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/conversations.json',
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

    'getConversationsBusyTimesReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/conversations/busy-times.json',
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
            ]
        ]
    ],

    'getNewConversationsReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/conversations/new.json',
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

    'getConversationsDrillDownReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/conversations/drilldown.json',
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
            ]
        ]
    ],

    'getConversationsDrillDownByFieldReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/conversations/fields-drilldown.json',
        'parameters' => [
            'start' => [
                'location' => 'query',
                'required' => true
            ],
            'end' => [
                'location' => 'query',
                'required' => true
            ],
            'field' => [
                'location' => 'query',
                'required' => true
            ],
            'fieldid' => [
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
            ]
        ]
    ],

    'getNewConversationsDrillDownReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/conversations/new-drilldown.json',
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
            ]
        ]
    ]

];
