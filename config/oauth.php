<?php

return [
    'website'=>[
        'facebook' => [
            'client_id'=>env('FACEBOOK_CLIENT_ID',''),
            'client_secret'=>env('FACEBOOK_CLIENT_SECRET',''),
            'redirect_uri'=>env('FACEBOOK_REDIRECT_URI',''),
            'token_uri'=>'https://graph.facebook.com/v10.0/oauth/access_token',
            'me_uri'=>'https://graph.facebook.com/me',
            'scopes'=>'public_profile%20email%20user_info',
            'fields'=>'id,name,email,locale',
            'site_name'=>'Facebook'
        ],
        'google'=>[
            'client_id'=>env('GOOGLE_CLIENT_ID',''),
            'client_secret'=>env('GOOGLE_CLIENT_SECRET',''),
            'redirect_uri'=>env('GOOGLE_REDIRECT_URI',''),
            'token_uri'=>'https://www.googleapis.com/oauth2/v4/token',
            'me_uri'=>'https://www.googleapis.com/oauth2/v2/tokeninfo',
            'site_name'=>'Google'
        ],
    ],'oauth_type_id'=>[
        1 => 'facebook',
        2 => 'google'
    ]
];

?>