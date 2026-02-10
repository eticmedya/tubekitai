<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Credit Costs
    |--------------------------------------------------------------------------
    |
    | Define the credit cost for each operation in the platform.
    | These values can be adjusted based on actual API costs.
    |
    */

    'costs' => [
        // High cost (3 credits)
        'comment_analysis' => 3,
        'competitor_analysis' => 3,
        'content_calendar_30d' => 2,
        'niche_analysis_detailed' => 2,

        // Medium cost (1-2 credits)
        'video_idea_generation' => 1,
        'channel_analysis' => 1,
        'trend_discovery' => 1,
        'keyword_analysis' => 1,
        'channel_dna_profile' => 1,
        'transflow_subtitle' => 1,

        // Low cost (0.5 credits)
        'title_suggestion' => 0.5,
        'ctr_prediction' => 0.5,
        'cover_analysis_score' => 0.5,

        // Image generation
        'cover_generation_fal' => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Credit Packages
    |--------------------------------------------------------------------------
    |
    | Available credit packages for purchase.
    | Prices are in kuruş (1/100 of TRY).
    |
    */

    'packages' => [
        'starter' => [
            'name' => 'Starter',
            'credits' => 15,
            'price' => 14900, // 149₺
            'description' => 'Perfect for getting started',
            'popular' => false,
        ],
        'creator' => [
            'name' => 'Creator',
            'credits' => 35,
            'price' => 29900, // 299₺
            'description' => 'Most popular for creators',
            'popular' => true,
        ],
        'pro' => [
            'name' => 'Pro',
            'credits' => 70,
            'price' => 49900, // 499₺
            'description' => 'For serious content creators',
            'popular' => false,
        ],
        'agency' => [
            'name' => 'Agency',
            'credits' => 150,
            'price' => 99900, // 999₺
            'description' => 'For agencies and teams',
            'popular' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Initial Credits
    |--------------------------------------------------------------------------
    |
    | Credits given to new users upon registration.
    |
    */

    'initial_credits' => 5,

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    */

    'currency' => 'TRY',
    'currency_symbol' => '₺',

];
