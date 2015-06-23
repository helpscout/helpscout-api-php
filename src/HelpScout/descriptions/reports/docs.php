<?php

return array(

    'getDocsReport' => array(
        'httpMethod' => 'GET',
        'uri' => 'reports/docs.json',
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
            'sites' => array(
                'location' => 'query'
            )
        )
    )

);
