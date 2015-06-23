<?php

return array(

    'getHappinessReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/happiness.json',
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

    'getHappinessRatingsReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/happiness/ratings.json',
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
