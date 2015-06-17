<?php

return [

    'getUserReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/user.json',
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

    'getUserConversationHistoryReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/user/conversation-history.json',
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
            'user' => [
                'location' => 'query',
                'required' => true
            ],
            'page' => [
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
    ],

    'getUserCustomersHelpedReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/user/customers-helped.json',
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
            ],
            'user' => [
                'location' => 'query',
                'required' => true
            ]
        ]
    ],

    'getUserDrillDownReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/users/drilldown.json',
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

    'getUserRepliesReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/user/replies.json',
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
            ],
            'user' => [
                'location' => 'query',
                'required' => true
            ]
        ]
    ],

    'getUserResolutionsReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/user/resolutions.json',
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
            ],
            'user' => [
                'location' => 'query',
                'required' => true
            ]
        ]
    ],

    'getUserHappinessReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/user/happiness.json',
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

    'getUserRatingsReport' => [
        'httpMethod' => 'GET',
        'uri' => 'reports/user/ratings.json',
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
            'user' => [
                'location' => 'query',
                'required' => true
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
