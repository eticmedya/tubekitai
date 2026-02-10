<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | YouTube API
    |--------------------------------------------------------------------------
    */

    'youtube' => [
        'api_key' => env('YOUTUBE_API_KEY'),
        'base_url' => 'https://www.googleapis.com/youtube/v3',
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Providers
    |--------------------------------------------------------------------------
    */

    'ai' => [
        'default_provider' => env('AI_DEFAULT_PROVIDER', 'openai'),

        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),  // Maliyet tasarrufu için
            'max_tokens' => env('OPENAI_MAX_TOKENS', 4096),
            'temperature' => env('OPENAI_TEMPERATURE', 0.7),
        ],

        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-3-5-sonnet-20241022'),
            'max_tokens' => env('ANTHROPIC_MAX_TOKENS', 4096),
            'temperature' => env('ANTHROPIC_TEMPERATURE', 0.7),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | fal.ai Image Generation
    |--------------------------------------------------------------------------
    */

    'fal' => [
        'api_key' => env('FAL_API_KEY'),
        'base_url' => 'https://fal.run',
        'model' => env('FAL_MODEL', 'fal-ai/nano-banana-pro'), // Gemini 3 Pro Image model
    ],

    /*
    |--------------------------------------------------------------------------
    | PayTR Payment Gateway
    |--------------------------------------------------------------------------
    */

    'paytr' => [
        'merchant_id' => env('PAYTR_MERCHANT_ID'),
        'merchant_key' => env('PAYTR_MERCHANT_KEY'),
        'merchant_salt' => env('PAYTR_MERCHANT_SALT'),
        'test_mode' => env('PAYTR_TEST_MODE', true),
        'base_url' => 'https://www.paytr.com/odeme/api/get-token',
        'timeout' => 30,
    ],

];
