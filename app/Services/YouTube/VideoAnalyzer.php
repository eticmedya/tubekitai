<?php

namespace App\Services\YouTube;

use App\Models\Channel;
use App\Models\User;
use App\Models\Video;
use App\Services\AI\AIManager;

class VideoAnalyzer
{
    public function __construct(
        protected YouTubeAPIService $youtube,
        protected AIManager $ai
    ) {}

    /**
     * Analyze a YouTube video.
     */
    public function analyze(string $videoUrl, User $user): array
    {
        $videoId = $this->youtube->extractVideoId($videoUrl);

        if (!$videoId) {
            throw new \Exception(__('youtube.invalid_video_url'));
        }

        $videoData = $this->youtube->getVideo($videoId);

        if (!$videoData) {
            throw new \Exception(__('youtube.video_not_found'));
        }

        // Calculate metrics
        $metrics = $this->calculateMetrics($videoData);

        // Generate AI analysis
        $aiAnalysis = $this->generateAIAnalysis($videoData, $metrics);

        // Save video to database
        $video = $this->saveVideo($user, $videoData);

        return [
            'video' => $video,
            'metrics' => $metrics,
            'ai_analysis' => $aiAnalysis,
        ];
    }

    /**
     * Calculate video metrics.
     */
    protected function calculateMetrics(array $videoData): array
    {
        $views = $videoData['view_count'];
        $likes = $videoData['like_count'];
        $comments = $videoData['comment_count'];

        $engagementRate = $views > 0 ? round((($likes + $comments) / $views) * 100, 2) : 0;
        $likeRate = $views > 0 ? round(($likes / $views) * 100, 2) : 0;
        $commentRate = $views > 0 ? round(($comments / $views) * 100, 4) : 0;

        // Performance rating
        $performanceScore = $this->calculatePerformanceScore($engagementRate, $views);

        return [
            'view_count' => $views,
            'like_count' => $likes,
            'comment_count' => $comments,
            'engagement_rate' => $engagementRate,
            'like_rate' => $likeRate,
            'comment_rate' => $commentRate,
            'duration_seconds' => $videoData['duration_seconds'],
            'performance_score' => $performanceScore,
            'performance_label' => $this->getPerformanceLabel($performanceScore),
        ];
    }

    /**
     * Calculate performance score.
     */
    protected function calculatePerformanceScore(float $engagementRate, int $views): int
    {
        $score = 0;

        // Engagement rate contribution (max 50 points)
        $score += min(50, $engagementRate * 5);

        // View count contribution (max 50 points)
        $score += match (true) {
            $views >= 1000000 => 50,
            $views >= 100000 => 40,
            $views >= 10000 => 30,
            $views >= 1000 => 20,
            $views >= 100 => 10,
            default => 5,
        };

        return min(100, (int) $score);
    }

    /**
     * Get performance label.
     */
    protected function getPerformanceLabel(int $score): string
    {
        return match (true) {
            $score >= 90 => __('video.performance.viral'),
            $score >= 75 => __('video.performance.excellent'),
            $score >= 60 => __('video.performance.good'),
            $score >= 40 => __('video.performance.average'),
            $score >= 20 => __('video.performance.below_average'),
            default => __('video.performance.needs_work'),
        };
    }

    /**
     * Generate AI analysis.
     */
    protected function generateAIAnalysis(array $videoData, array $metrics): array
    {
        $tags = is_array($videoData['tags']) ? implode(', ', array_slice($videoData['tags'], 0, 10)) : '';
        $locale = app()->getLocale();

        if ($locale === 'tr') {
            $prompt = <<<PROMPT
Bu YouTube videosunu analiz et ve içgörüler sun:

Başlık: {$videoData['title']}
Açıklama: {$videoData['description']}
Görüntüleme: {$metrics['view_count']}
Beğeni: {$metrics['like_count']}
Yorum: {$metrics['comment_count']}
Etkileşim Oranı: {$metrics['engagement_rate']}%
Süre: {$metrics['duration_seconds']} saniye
Etiketler: {$tags}

Lütfen şunları sun (TÜM YANITLAR TÜRKÇE OLMALI):
1. Başlık Analizi (SEO etkinliği, tıklanabilirlik, duygusal tetikleyiciler)
2. Açıklama Optimizasyon Önerileri
3. Etiket Tavsiyeleri
4. Videonun neden bu şekilde performans gösterdiği
5. Thumbnail Önerileri (başlık ve içeriğe dayalı)
6. Oluşturulabilecek benzer içerik fikirleri

ÖNEMLİ: Tüm analizi TÜRKÇE yaz. İngilizce kullanma.
PROMPT;
            $systemPrompt = 'Sen uzman bir YouTube video analistisin. İçerik üreticilerinin performansını artırmalarına yardımcı olmak için detaylı, uygulanabilir öneriler sun. MUTLAKA tüm yanıtlarını Türkçe olarak ver.';
        } else {
            $prompt = <<<PROMPT
Analyze this YouTube video and provide insights:

Title: {$videoData['title']}
Description: {$videoData['description']}
Views: {$metrics['view_count']}
Likes: {$metrics['like_count']}
Comments: {$metrics['comment_count']}
Engagement Rate: {$metrics['engagement_rate']}%
Duration: {$metrics['duration_seconds']} seconds
Tags: {$tags}

Please provide:
1. Title Analysis (SEO effectiveness, click-worthiness, emotional triggers)
2. Description Optimization Suggestions
3. Tag Recommendations
4. Why this video performed the way it did
5. Thumbnail Suggestions (based on title and content)
6. Similar content ideas to create
PROMPT;
            $systemPrompt = 'You are an expert YouTube video analyst. Provide detailed, actionable insights to help creators improve their content performance.';
        }

        $response = $this->ai->analyze($prompt, [
            'system_prompt' => $systemPrompt,
        ]);

        return [
            'summary' => $response,
            'model_used' => $this->ai->getModelName(),
        ];
    }

    /**
     * Save video to database.
     */
    protected function saveVideo(User $user, array $videoData): Video
    {
        // Find or create channel
        $channel = Channel::firstOrCreate(
            ['youtube_id' => $videoData['channel_id']],
            [
                'user_id' => $user->id,
                'title' => $videoData['channel_title'] ?? 'Unknown Channel',
            ]
        );

        return Video::updateOrCreate(
            ['youtube_id' => $videoData['youtube_id']],
            [
                'channel_id' => $channel->id,
                'title' => $videoData['title'],
                'description' => $videoData['description'],
                'view_count' => $videoData['view_count'],
                'like_count' => $videoData['like_count'],
                'comment_count' => $videoData['comment_count'],
                'thumbnail_url' => $videoData['thumbnail_url'],
                'duration' => $videoData['duration'],
                'duration_seconds' => $videoData['duration_seconds'],
                'tags' => $videoData['tags'],
                'category_id' => $videoData['category_id'],
                'default_language' => $videoData['default_language'],
                'published_at' => $videoData['published_at'],
            ]
        );
    }
}
