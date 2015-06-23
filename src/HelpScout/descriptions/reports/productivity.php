<?php

return array(

    'getProductivityReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity.json',
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
            'officeHours' => array(
                'location' => 'query'
            )
        )
    ),

    'getFirstResponseTimeProductivityReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity/first-response-time.json',
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
            'officeHours' => array(
                'location' => 'query'
            ),
            'viewBy' => array(
                'location' => 'query'
            )
        )
    ),

    'getRepliesSentProductivityReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity/replies-sent.json',
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

    'getResolvedProductivityReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity/resolved.json',
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

    'getResolutionTimeProductivityReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity/resolution-time.json',
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
            'officeHours' => array(
                'location' => 'query'
            ),
            'viewBy' => array(
                'location' => 'query'
            )
        )
    ),

    'getResponseTimeProductivityReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity/response-time.json',
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
            'officeHours' => array(
                'location' => 'query'
            ),
            'viewBy' => array(
                'location' => 'query'
            )
        )
    ),

    'getProductivityDrillDownReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/productivity/drilldown.json',
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
            ),
            'range' => array(
                'location' => 'query',
                'required' => true
            ),
            'rangeId' => array(
                'location' => 'query'
            )
        )
    )

);
