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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    // LigdiCash — agrégateur mobile money (Mixx by Yas / Flooz).
    'ligdicash' => [
        'base_url' => env('LIGDICASH_BASE_URL', 'https://app.ligdicash.com'),
        'api_key' => env('LIGDICASH_API_KEY'),
        'auth_token' => env('LIGDICASH_AUTH_TOKEN'),
        'callback_url' => env('LIGDICASH_CALLBACK_URL'),
        'store_name' => env('LIGDICASH_STORE_NAME', 'COOPEC-AD'),
        // Mode démo : simule le flux de paiement (USSD push) sans appeler l'API
        // réelle de LigdiCash. Null = automatique (activé si les identifiants
        // ne sont pas renseignés). Utile pour la soutenance.
        'demo' => env('LIGDICASH_DEMO'),
    ],

];
