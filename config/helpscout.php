<?php

return [

    'auth' => [
        /*
        |--------------------------------------------------------------------------
        | Authentication Type
        |--------------------------------------------------------------------------
        |
        | The SDK will allow you to use either legacy credentials for apps created
        | using the Mailbox API v1 or the client credentials grant for apps that
        | were created using the Mailbox API v2. Valid values for this field
        | are `client_credentials`, `legacy_token`, or simply null.
        |
        */
        'type' => env('HS_AUTH_TYPE', 'client_credentials'),

        /*
        |--------------------------------------------------------------------------
        | Application ID
        |--------------------------------------------------------------------------
        |
        | Get this value from the `/users/apps/{userId}/{appSlug}` page within
        | the Help Scout UI. This field is required if you are using the
        | `client_credentials` grant.
        |
        */
        'appId' => env('HS_APP_ID', ''),

        /*
        |--------------------------------------------------------------------------
        | Application Secret
        |--------------------------------------------------------------------------
        |
        | Get this value from the My Apps page within the Help Scout UI. This field
        | is required if you are using the `client_credentials` grant.
        |
        */
        'appSecret' => env('HS_APP_SECRET', ''),

        /*
        |--------------------------------------------------------------------------
        | Legacy Client ID
        |--------------------------------------------------------------------------
        |
        | Get this value from the `/users/apps/{userId}/{appSlug}` page within
        | the Help Scout UI in the "App ID" field. This field is required if
        | use are using the `legacy_token` auth credentials
        |
        */
        'clientId' => env('HS_CLIENT_ID', ''),

        /*
        |--------------------------------------------------------------------------
        | Legacy API Key
        |--------------------------------------------------------------------------
        |
        | Get this value from the `/users/authentication/{userId}/api-keys` page
        | within the Help Scout UI. This field is required if you are using
        | the `legacy_token` auth credentials.
        |
        */
        'apiKey' => env('HS_API_KEY', ''),
    ]

];
