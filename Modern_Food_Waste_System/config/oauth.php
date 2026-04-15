<?php
// config/oauth.php

return [
    /* 
    | SOCIAL LOGIN CONFIGURATION
    |--------------------------------------------------------------------------
    | DEMO MODE: If client_id starts with 'YOUR_', the system will use a 
    | simulated login with mock profiles. This allows testing without real keys.
    |
    | REAL MODE: Replace placeholders with your own keys from:
    | - Google: https://console.cloud.google.com/
    | - Facebook: https://developers.facebook.com/
    | - LinkedIn: https://www.linkedin.com/developers/
    */

    'google' => [
        'client_id' => 'YOUR_GOOGLE_CLIENT_ID', 
        'client_secret' => 'YOUR_GOOGLE_CLIENT_SECRET',
        'redirect_uri' => 'http://localhost/Modern_Food_Waste_System/src/social_auth.php?provider=google',
    ],
    'facebook' => [
        'client_id' => 'YOUR_FACEBOOK_CLIENT_ID',
        'client_secret' => 'YOUR_FACEBOOK_CLIENT_SECRET',
        'redirect_uri' => 'http://localhost/Modern_Food_Waste_System/src/social_auth.php?provider=facebook',
    ],
    'linkedin' => [
        'client_id' => 'YOUR_LINKEDIN_CLIENT_ID',
        'client_secret' => 'YOUR_LINKEDIN_CLIENT_SECRET',
        'redirect_uri' => 'http://localhost/Modern_Food_Waste_System/src/social_auth.php?provider=linkedin',
    ]
];
