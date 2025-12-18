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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'mora' => [
        'url'      => env('MORA_SMS_URL', 'https://www.mora-sa.com/api/v1/sendsms'),
        'username' => env('MORA_SMS_USERNAME'),
        'api_key'  => env('MORA_SMS_API_KEY'),
        'sender'   => env('MORA_SMS_SENDER', 'ASKILA'),
        'response' => env('MORA_SMS_RESPONSE_TYPE', 'json'),
        'unicode'  => (bool) env('MORA_SMS_UNICODE', true),
        'timeout'  => (int) env('MORA_SMS_TIMEOUT', 10),
        // اختياري: تبديل سريع لإيقاف الإرسال من الإعدادات
        'enabled'  => (bool) env('MORA_SMS_ENABLED', true),
        // 'timeout'  => 10,
    ],

];
