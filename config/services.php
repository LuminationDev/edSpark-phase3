<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // 'okta' => [
    //     'client_id' => env('OKTA_CLIENT_ID'),
    //     'client_secret' => env('OKTA_CLIENT_SECRET'),
    //     'redirect' => env('OKTA_REDIRECT_URI'),
    //     'issuer' => env('OKTA_ISSUER'),
    // ],

    'okta' => [
        'base_url' => env('OKTA_BASE_URL'),
        'client_id' => env('OKTA_CLIENT_ID'),
        'client_secret' => env('OKTA_CLIENT_SECRET'),
        'redirect' => env('OKTA_REDIRECT_URI'),
        'authorization_server_id' => env('OKTA_AUTH_SERVER_URL','default'),
        'auth_server_id' => env('OKTA_AUTH_SERVER_URL', 'default'),
        'scopes' => ['edspark','email','address']
      ],


];
