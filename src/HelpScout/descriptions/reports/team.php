<?php

return array(

    'getTeamReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/team.json',
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

    'getCustomersHelpedTeamReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/team/customers-helped.json',
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

    'getTeamDrillDownReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/team/drilldown.json',
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
