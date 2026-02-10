<?php

namespace App\Services\YouTube;

use App\Models\Channel;
use App\Models\User;
use App\Services\AI\AIManager;
use Illuminate\Support\Collection;

class ChannelAnalyzer
{
    public function __construct(
        protected YouTubeAPIService $youtube,
        protected AIManager $ai
    ) {}

    /**
     * Analyze a YouTube channel.
     */
    public function analyze(string $channelUrl, User $user): array
    {
        // Fetch channel data
        $channelData = $this->youtube->getChannel($channelUrl);

        if (!$channelData) {
            throw new \Exception(__('youtube.channel_not_found'));
        }

        // Fetch recent videos
        $videos = $this->youtube->getChannelVideos($channelData['youtube_id'], 30);

        // Calculate metrics
        $metrics = $this->calculateMetrics($channelData, $videos);

        // Generate AI analysis
        $aiAnalysis = $this->generateAIAnalysis($channelData, $videos, $metrics);

        // Save channel to database
        $channel = $this->saveChannel($user, $channelData);

        return [
            'channel' => $channel,
            'metrics' => $metrics,
            'top_videos' => $videos->sortByDesc('view_count')->take(5)->values(),
            'ai_analysis' => $aiAnalysis,
        ];
    }

    /**
     * Calculate channel metrics.
     */
    protected function calculateMetrics(array $channelData, Collection $videos): array
    {
        $totalViews = $videos->sum('view_count');
        $totalLikes = $videos->sum('like_count');
        $totalComments = $videos->sum('comment_count');
        $videoCount = $videos->count();

        $avgViews = $videoCount > 0 ? round($totalViews / $videoCount) : 0;
        $avgLikes = $videoCount > 0 ? round($totalLikes / $videoCount) : 0;
        $avgEngagement = $totalViews > 0 ? round((($totalLikes + $totalComments) / $totalViews) * 100, 2) : 0;

        // Calculate upload frequency
        $uploadDates = $videos->pluck('published_at')->filter()->sort();
        $uploadFrequency = $this->calculateUploadFrequency($uploadDates);

        // Performance score (0-100)
        $performanceScore = $this->calculatePerformanceScore($channelData, $avgViews, $avgEngagement);

        return [
            'subscriber_count' => $channelData['subscriber_count'],
            'total_views' => $channelData['view_count'],
            'video_count' => $channelData['video_count'],
            'avg_views_per_video' => $avgViews,
            'avg_likes_per_video' => $avgLikes,
            'engagement_rate' => $avgEngagement,
            'upload_frequency' => $uploadFrequency,
            'performance_score' => $performanceScore,
            'views_to_subs_ratio' => $channelData['subscriber_count'] > 0
                ? round($avgViews / $channelData['subscriber_count'] * 100, 2)
                : 0,
        ];
    }

    /**
     * Calculate upload frequency.
     */
    protected function calculateUploadFrequency(Collection $dates): array
    {
        if ($dates->count() < 2) {
            return [
                'per_week' => 0,
                'per_month' => 0,
                'label' => __('youtube.upload_frequency.unknown'),
            ];
        }

        $firstDate = \Carbon\Carbon::parse($dates->first());
        $lastDate = \Carbon\Carbon::parse($dates->last());
        $daysDiff = $firstDate->diffInDays($lastDate) ?: 1;
        $videoCount = $dates->count();

        $perWeek = round(($videoCount / $daysDiff) * 7, 1);
        $perMonth = round(($videoCount / $daysDiff) * 30, 1);

        $label = match (true) {
            $perWeek >= 7 => __('youtube.upload_frequency.daily'),
            $perWeek >= 3 => __('youtube.upload_frequency.several_week'),
            $perWeek >= 1 => __('youtube.upload_frequency.weekly'),
            $perMonth >= 2 => __('youtube.upload_frequency.biweekly'),
            $perMonth >= 1 => __('youtube.upload_frequency.monthly'),
            default => __('youtube.upload_frequency.irregular'),
        };

        return [
            'per_week' => $perWeek,
            'per_month' => $perMonth,
            'label' => $label,
        ];
    }

    /**
     * Calculate performance score.
     */
    protected function calculatePerformanceScore(array $channelData, int $avgViews, float $engagement): int
    {
        $score = 0;

        // Views to subscribers ratio (max 30 points)
        $viewsToSubs = $channelData['subscriber_count'] > 0
            ? ($avgViews / $channelData['subscriber_count']) * 100
            : 0;
        $score += min(30, $viewsToSubs * 3);

        // Engagement rate (max 30 points)
        $score += min(30, $engagement * 3);

        // Subscriber count (max 20 points)
        $score += match (true) {
            $channelData['subscriber_count'] >= 1000000 => 20,
            $channelData['subscriber_count'] >= 100000 => 15,
            $channelData['subscriber_count'] >= 10000 => 10,
            $channelData['subscriber_count'] >= 1000 => 5,
            default => 2,
        };

        // Video count (max 20 points)
        $score += match (true) {
            $channelData['video_count'] >= 500 => 20,
            $channelData['video_count'] >= 100 => 15,
            $channelData['video_count'] >= 50 => 10,
            $channelData['video_count'] >= 20 => 5,
            default => 2,
        };

        return min(100, (int) $score);
    }

    /**
     * Generate AI analysis.
     */
    protected function generateAIAnalysis(array $channelData, Collection $videos, array $metrics): array
    {
        $topVideos = $videos->sortByDesc('view_count')->take(5);
        $topTitles = $topVideos->pluck('title')->toArray();
        $topTags = $videos->pluck('tags')->flatten()->countBy()->sortDesc()->take(20)->keys()->toArray();

        $prompt = <<<PROMPT
Analyze this YouTube channel and provide actionable insights in the user's language (Turkish if the channel appears Turkish, otherwise English):

Channel: {$channelData['title']}
Description: {$channelData['description']}
Subscribers: {$metrics['subscriber_count']}
Total Views: {$metrics['total_views']}
Videos: {$metrics['video_count']}
Avg Views per Video: {$metrics['avg_views_per_video']}
Engagement Rate: {$metrics['engagement_rate']}%
Upload Frequency: {$metrics['upload_frequency']['per_week']} per week

Top Video Titles:
- {$topTitles[0]}
- {$topTitles[1]}
- {$topTitles[2]}
- {$topTitles[3]}
- {$topTitles[4]}

Common Tags: [tags]

Please provide:
1. Channel DNA Profile (content style, target audience, niche)
2. Top 3 Strengths
3. Top 3 Areas for Improvement
4. Content Strategy Recommendations
5. Title Pattern Analysis (what works for this channel)
6. Growth Opportunities
PROMPT;

        $prompt = str_replace('[tags]', implode(', ', $topTags), $prompt);

        $response = $this->ai->analyze($prompt, [
            'system_prompt' => 'You are an expert YouTube channel analyst. Provide detailed, actionable insights based on the data provided. Be specific and practical.',
        ]);

        return [
            'summary' => $response,
            'top_tags' => $topTags,
            'model_used' => $this->ai->getModelName(),
        ];
    }

    /**
     * Save channel to database.
     */
    protected function saveChannel(User $user, array $channelData): Channel
    {
        return Channel::updateOrCreate(
            ['youtube_id' => $channelData['youtube_id']],
            [
                'user_id' => $user->id,
                'title' => $channelData['title'],
                'description' => $channelData['description'],
                'subscriber_count' => $channelData['subscriber_count'],
                'video_count' => $channelData['video_count'],
                'view_count' => $channelData['view_count'],
                'thumbnail_url' => $channelData['thumbnail_url'],
                'custom_url' => $channelData['custom_url'],
                'country' => $channelData['country'],
                'published_at' => $channelData['published_at'],
                'analyzed_at' => now(),
            ]
        );
    }
}
