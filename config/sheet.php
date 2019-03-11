<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Sheet Auth  
    |--------------------------------------------------------------------------
    |
    | This array is configration of Google Sheet Auth. This array is used when the
    | framework needs to create the connection and get the access_token from 
    | Google api.
    |
    */
    
    'auth' => [
        'type' => 'service_account',
        'project_id' => env('GOOGLE_PROJECT_ID'),
        'private_key_id' => env('GOOGLE_PRIVATE_KEY_ID'),
        'private_key' => env('GOOGLE_PRIVATE_KEY'),
        'client_email' => env('GOOGLE_CLIENT_EMAIL'),
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
        'token_uri' => 'https://oauth2.googleapis.com/token',
        'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
        'client_x509_cert_url' => env('GOOGLE_CLIENT_X509_CERT_URL'),
    ],
];
