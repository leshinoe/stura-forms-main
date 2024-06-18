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

    'saml2' => [
        /** IdP */
        'metadata' => env('SAML2_IDP_METADATA', 'https://www.b-tu.de/idp/shibboleth'),
        'entityid' => env('SAML2_IDP_ENTITYID', 'https://www.b-tu.de/idp/shibboleth'),

        /** SP */
        'sp_entityid' => env('APP_URL').'/auth/saml2',

        'sp_tech_contact_surname' => 'Kiekbusch',
        'sp_tech_contact_givenname' => 'Julius',
        'sp_tech_contact_email' => 'contact@julius-kiekbusch.de',
        'sp_org_lang' => 'de',
        'sp_org_name' => 'Studierendenrat der Brandenburgischen Technischen Universität Cottbus-Senftenberg',
        'sp_org_display_name' => 'StuRa der BTU Cottbus-Senftenberg',
        'sp_org_url' => 'https://www.stura-btu.de',

        /** Routes */
        'sp_acs' => env('APP_URL').'/auth/saml2/callback',

        /** Signing
         * openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:4096 -keyout storage/app/keys/sp_saml.pem -out storage/app/keys/sp_saml.crt
         */
        'sp_sign_assertions' => true,

        // 'path/to/sp_saml.crt'
        'sp_certificate' => file_get_contents(storage_path('app/keys/sp_saml.crt')),

        // 'path/to/sp_saml.pem'
        'sp_private_key' => file_get_contents(storage_path('app/keys/sp_saml.pem')),

        'sp_private_key_passphrase' => env('SAML2_SP_PRIVATE_KEY_PASSPHRASE'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
