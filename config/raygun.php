<?php

/*
|--------------------------------------------------------------------------
| Raygun configuration
|--------------------------------------------------------------------------
|
| More details (https://raygun.com/documentation/language-guides/php/crash-reporting/installation/)
|
*/

return [
    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | You can find your Raygun API key in your Raygun Application settings > General.
    |
    */
    'api_key' => 'your_raygun_api_key',

    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | Optional, the version number of your PHP project, such as 1.0.0
    |
    */
    'version' => null,

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    |
    | Optional, should be a unique identifier, id, phone or email.
    | it can be wrapped in a closure to get the current user dynamically.
    |
    */
    'user' => null,
    //'user' => 'test@test.com',
    //'user' => fn() => Yii::$app?->user->identity?->id,

    /*
    |--------------------------------------------------------------------------
    | Filter Params
    |--------------------------------------------------------------------------
    |
    | Optional, These transformations apply to form data ($_POST),
    | custom user data, HTTP headers, and environment data ($_SERVER).
    | It does not filter the URL or its $_GET parameters
    |
    */
    'filter_params' => [
        'password',
        'authorization',
        '/^credit/i',
        'ccv',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom data
    |--------------------------------------------------------------------------
    |
    | Optional, Custom data can be added as an associative array to the error report
    |
    */
    'custom_data' => [],

    /*
    |--------------------------------------------------------------------------
    | Tags
    |--------------------------------------------------------------------------
    |
    | Optional, Tags can be added to error data to provide extra information
    | and to help filtering errors within Raygun.
    |
    */
    'tags' => [],
    //'tags' => ['local-environment'],
];
