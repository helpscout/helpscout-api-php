<?php

return array(

    'getUserReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/user.json',
        'parameters' => array(
            'start' => array(
                'location' => 'query',
                'required' => true
            ),
            'end' => array(
                'location' => 'query',
                'required' => true
            ),
            'previousStart' => array(
                'location' => 'query'
            ),
            'previousEnd' => array(
                'location' => 'query'
            ),
            'mailboxes' => array(
                'location' => 'query'
            ),
            'tags' => array(
                'location' => 'query'
            ),
            'types' => array(
                'location' => 'query'
            ),
            'folders' => array(
                'location' => 'query'
            ),
            'user' => array(
                'location' => 'query',
                'required' => true
            )
        )
    ),

    'getUserConversationHistoryReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/user/conversation-history.json',
        'parameters' => array(
            'start' => array(
                'location' => 'query',
                'required' => true
            ),
            'end' => array(
                'location' => 'query',
                'required' => true
            ),
            'mailboxes' => array(
                'location' => 'query'
            ),
            'tags' => array(
                'location' => 'query'
            ),
            'types' => array(
                'location' => 'query'
            ),
            'folders' => array(
                'location' => 'query'
            ),
            'user' => array(
                'location' => 'query',
                'required' => true
            ),
            'page' => array(
                'location' => 'query',
                'required' => true
            ),
            'sortField' => array(
                'location' => 'query'
            ),
            'sortOrder' => array(
                'location' => 'query'
            )
        )
    ),

    'getUserCustomersHelpedReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/user/customers-helped.json',
        'parameters' => array(
            'start' => array(
                'location' => 'query',
                'required' => true
            ),
            'end' => array(
                'location' => 'query',
                'required' => true
            ),
            'previousStart' => array(
                'location' => 'query'
            ),
            'previousEnd' => array(
                'location' => 'query'
            ),
            'mailboxes' => array(
                'location' => 'query'
            ),
            'tags' => array(
                'location' => 'query'
            ),
            'types' => array(
                'location' => 'query'
            ),
            'folders' => array(
                'location' => 'query'
            ),
            'viewBy' => array(
                'location' => 'query'
            ),
            'user' => array(
                'location' => 'query',
                'required' => true
            )
        )
    ),

    'getUserDrillDownReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/users/drilldown.json',
        'parameters' => array(
            'start' => array(
                'location' => 'query',
                'required' => true
            ),
            'end' => array(
                'location' => 'query',
                'required' => true
            ),
            'mailboxes' => array(
                'location' => 'query'
            ),
            'tags' => array(
                'location' => 'query'
            ),
            'types' => array(
                'location' => 'query'
            ),
            'folders' => array(
                'location' => 'query'
            ),
            'page' => array(
                'location' => 'query'
            ),
            'rows' => array(
                'location' => 'query'
            )
        )
    ),

    'getUserRepliesReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/user/replies.json',
        'parameters' => array(
            'start' => array(
                'location' => 'query',
                'required' => true
            ),
            'end' => array(
                'location' => 'query',
                'required' => true
            ),
            'previousStart' => array(
                'location' => 'query'
            ),
            'previousEnd' => array(
                'location' => 'query'
            ),
            'mailboxes' => array(
                'location' => 'query'
            ),
            'tags' => array(
                'location' => 'query'
            ),
            'types' => array(
                'location' => 'query'
            ),
            'folders' => array(
                'location' => 'query'
            ),
            'viewBy' => array(
                'location' => 'query'
            ),
            'user' => array(
                'location' => 'query',
                'required' => true
            )
        )
    ),

    'getUserResolutionsReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/user/resolutions.json',
        'parameters' => array(
            'start' => array(
                'location' => 'query',
                'required' => true
            ),
            'end' => array(
                'location' => 'query',
                'required' => true
            ),
            'previousStart' => array(
                'location' => 'query'
            ),
            'previousEnd' => array(
                'location' => 'query'
            ),
            'mailboxes' => array(
                'location' => 'query'
            ),
            'tags' => array(
                'location' => 'query'
            ),
            'types' => array(
                'location' => 'query'
            ),
            'folders' => array(
                'location' => 'query'
            ),
            'viewBy' => array(
                'location' => 'query'
            ),
            'user' => array(
                'location' => 'query',
                'required' => true
            )
        )
    ),

    'getUserHappinessReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/user/happiness.json',
        'parameters' => array(
            'start' => array(
                'location' => 'query',
                'required' => true
            ),
            'end' => array(
                'location' => 'query',
                'required' => true
            ),
            'previousStart' => array(
                'location' => 'query'
            ),
            'previousEnd' => array(
                'location' => 'query'
            ),
            'mailboxes' => array(
                'location' => 'query'
            ),
            'tags' => array(
                'location' => 'query'
            ),
            'types' => array(
                'location' => 'query'
            ),
            'folders' => array(
                'location' => 'query'
            ),
            'user' => array(
                'location' => 'query',
                'required' => true
            )
        )
    ),

    'getUserRatingsReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/user/ratings.json',
        'parameters' => array(
            'start' => array(
                'location' => 'query',
                'required' => true
            ),
            'end' => array(
                'location' => 'query',
                'required' => true
            ),
            'mailboxes' => array(
                'location' => 'query'
            ),
            'tags' => array(
                'location' => 'query'
            ),
            'types' => array(
                'location' => 'query'
            ),
            'folders' => array(
                'location' => 'query'
            ),
            'user' => array(
                'location' => 'query',
                'required' => true
            ),
            'page' => array(
                'location' => 'query',
                'required' => true
            ),
            'rating' => array(
                'location' => 'query',
                'required' => true
            ),
            'sortField' => array(
                'location' => 'query'
            ),
            'sortOrder' => array(
                'location' => 'query'
            )
        )
    )

);
