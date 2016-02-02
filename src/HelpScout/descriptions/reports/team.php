<?php

$companyReport = array(
    'httpMethod' => 'GET',
    'uri' => 'reports/company.json',
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
);

$customersHelpedCompanyReport = array(
    'httpMethod' => 'GET',
    'uri' => 'reports/company/customers-helped.json',
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
);

$companyDrillDownReport = array(
    'httpMethod' => 'GET',
    'uri' => 'reports/company/drilldown.json',
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
);

return array(

    'getCompanyReport' => $companyReport,

    // Deprecated, use `getCompanyReport`
    'getTeamReport' => $companyReport,

    'getCustomersHelpedCompanyReport' => $customersHelpedCompanyReport,

    // Deprecated, use `getCustomersHelpedCompanyReport
    'getCustomersHelpedTeamReport' => $customersHelpedCompanyReport,

    'getCompanyDrillDownReport' => $companyDrillDownReport,

    // Deprecated, use `getCompanyDrillDownReport`
    'getTeamDrillDownReport' => $companyDrillDownReport

);
