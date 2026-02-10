<?php

namespace App\Enums;

enum OperationType: string
{
    // High cost (3-6 credits)
    case COMMENT_ANALYSIS = 'comment_analysis';
    case TRANSFLOW_SUBTITLE = 'transflow_subtitle';
    case COMPETITOR_ANALYSIS = 'competitor_analysis';
    case CONTENT_CALENDAR_30D = 'content_calendar_30d';
    case NICHE_ANALYSIS_DETAILED = 'niche_analysis_detailed';

    // Medium cost (1 credit)
    case VIDEO_IDEA_GENERATION = 'video_idea_generation';
    case CHANNEL_ANALYSIS = 'channel_analysis';
    case TREND_DISCOVERY = 'trend_discovery';
    case KEYWORD_ANALYSIS = 'keyword_analysis';
    case CHANNEL_DNA_PROFILE = 'channel_dna_profile';

    // Low cost (0.5 credits)
    case TITLE_SUGGESTION = 'title_suggestion';
    case CTR_PREDICTION = 'ctr_prediction';
    case COVER_ANALYSIS_SCORE = 'cover_analysis_score';

    // Image generation
    case COVER_GENERATION_FAL = 'cover_generation_fal';

    public function getCost(): float
    {
        return config('credits.costs.' . $this->value, 1);
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::COMMENT_ANALYSIS => __('modules.comment_analysis'),
            self::TRANSFLOW_SUBTITLE => __('modules.transflow'),
            self::COMPETITOR_ANALYSIS => __('modules.competitor_analysis'),
            self::CONTENT_CALENDAR_30D => __('modules.content_calendar'),
            self::NICHE_ANALYSIS_DETAILED => __('modules.niche_analysis'),
            self::VIDEO_IDEA_GENERATION => __('modules.video_ideas'),
            self::CHANNEL_ANALYSIS => __('modules.channel_analysis'),
            self::TREND_DISCOVERY => __('modules.trend_discovery'),
            self::KEYWORD_ANALYSIS => __('modules.keyword_trends'),
            self::CHANNEL_DNA_PROFILE => __('modules.channel_dna'),
            self::TITLE_SUGGESTION => __('modules.title_suggestion'),
            self::CTR_PREDICTION => __('modules.ctr_prediction'),
            self::COVER_ANALYSIS_SCORE => __('modules.cover_analysis'),
            self::COVER_GENERATION_FAL => __('modules.cover_generation'),
        };
    }
}
