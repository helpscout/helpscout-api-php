<?php

return array(

    'getConversationsReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/conversations.json',
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
            )
        )
    ),

    'getConversationsBusyTimesReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/conversations/busy-times.json',
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
            )
        )
    ),

    'getNewConversationsReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/conversations/new.json',
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
            )
        )
    ),

    'getConversationsDrillDownReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/conversations/drilldown.json',
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

    'getConversationsDrillDownByFieldReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/conversations/customfields-drilldown.json',
        'parameters' => array(
            'start' => array(
                'location' => 'query',
                'required' => true
            ),
            'end' => array(
                'location' => 'query',
                'required' => true
            ),
            'field' => array(
                'location' => 'query',
                'required' => true
            ),
            'fieldid' => array(
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

    'getNewConversationsDrillDownReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/conversations/new-drilldown.json',
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
    )

);
