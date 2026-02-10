<?php

namespace App\Services\YouTube;

use App\Enums\CommentCategory;
use App\Models\Comment;
use App\Models\CommentAnalysis;
use App\Models\User;
use App\Models\Video;
use App\Services\AI\AIManager;
use Illuminate\Support\Collection;

class CommentAnalyzer
{
    public function __construct(
        protected YouTubeAPIService $youtube,
        protected AIManager $ai
    ) {}

    /**
     * Analyze comments for a video.
     */
    public function analyze(string $videoUrl, User $user, int $limit = 100): array
    {
        $videoId = $this->youtube->extractVideoId($videoUrl);

        if (!$videoId) {
            throw new \Exception(__('youtube.invalid_video_url'));
        }

        // Fetch video data
        $videoData = $this->youtube->getVideo($videoId);

        if (!$videoData) {
            throw new \Exception(__('youtube.video_not_found'));
        }

        // Fetch comments
        $comments = $this->youtube->getVideoComments($videoId, $limit);

        if ($comments->isEmpty()) {
            throw new \Exception(__('youtube.no_comments'));
        }

        // Categorize comments using AI
        $categorizedComments = $this->categorizeComments($comments);

        // Generate summary
        $summary = $this->generateSummary($videoData, $categorizedComments);

        // Save analysis to database
        $video = $this->saveVideo($user, $videoData);
        $analysis = $this->saveAnalysis($user, $video, $categorizedComments, $summary);

        return [
            'video' => $video,
            'analysis' => $analysis,
            'comments' => $categorizedComments,
            'summary' => $summary,
        ];
    }

    /**
     * Categorize comments using AI.
     */
    protected function categorizeComments(Collection $comments): array
    {
        $categorized = [
            'positive' => [],
            'negative' => [],
            'supportive' => [],
            'criticism' => [],
            'suggestion' => [],
            'question' => [],
            'toxic' => [],
            'neutral' => [],
        ];

        // Process in batches
        $batches = $comments->chunk(20);

        foreach ($batches as $batch) {
            $commentsText = $batch->map(function ($comment, $index) {
                return "[{$index}] " . mb_substr($comment['text'], 0, 500);
            })->implode("\n\n");

            $prompt = <<<PROMPT
Categorize each comment by its number. Return JSON only with this format:
{"0": "category", "1": "category", ...}

Categories: positive, negative, supportive, criticism, suggestion, question, toxic, neutral

Comments:
{$commentsText}
PROMPT;

            try {
                $result = $this->ai->generateJson($prompt, [], [
                    'system_prompt' => 'You are a comment sentiment analyzer. Categorize YouTube comments accurately. Return only valid JSON.',
                    'temperature' => 0.3,
                ]);

                foreach ($batch as $index => $comment) {
                    $category = $result[(string) $index] ?? 'neutral';
                    if (isset($categorized[$category])) {
                        $comment['category'] = $category;
                        $categorized[$category][] = $comment;
                    } else {
                        $comment['category'] = 'neutral';
                        $categorized['neutral'][] = $comment;
                    }
                }
            } catch (\Exception $e) {
                // On failure, mark all as neutral
                foreach ($batch as $comment) {
                    $comment['category'] = 'neutral';
                    $categorized['neutral'][] = $comment;
                }
            }
        }

        return $categorized;
    }

    /**
     * Generate summary of comments.
     */
    protected function generateSummary(array $videoData, array $categorizedComments): array
    {
        $counts = [];
        $allComments = [];

        foreach ($categorizedComments as $category => $comments) {
            $counts[$category] = count($comments);
            foreach ($comments as $comment) {
                $allComments[] = $comment;
            }
        }

        // Get top suggestions and criticisms
        $topSuggestions = collect($categorizedComments['suggestion'])
            ->sortByDesc('like_count')
            ->take(5)
            ->pluck('text')
            ->toArray();

        $topCriticisms = collect($categorizedComments['criticism'])
            ->sortByDesc('like_count')
            ->take(5)
            ->pluck('text')
            ->toArray();

        // Generate AI summary
        $totalComments = array_sum($counts);
        $positiveRatio = $totalComments > 0
            ? round((($counts['positive'] + $counts['supportive']) / $totalComments) * 100)
            : 0;

        $locale = app()->getLocale();

        if ($locale === 'tr') {
            $prompt = <<<PROMPT
Bu video için izleyici geri bildirimlerini özetle:

Video Başlığı: {$videoData['title']}

Yorum İstatistikleri:
- Toplam Analiz Edilen Yorum: {$totalComments}
- Pozitif: {$counts['positive']}
- Destekleyici: {$counts['supportive']}
- Negatif: {$counts['negative']}
- Eleştiri: {$counts['criticism']}
- Öneri: {$counts['suggestion']}
- Soru: {$counts['question']}
- Toxic: {$counts['toxic']}
- Nötr: {$counts['neutral']}

İzleyicilerden gelen öne çıkan öneriler:
{$this->formatList($topSuggestions)}

İzleyicilerden gelen öne çıkan eleştiriler:
{$this->formatList($topCriticisms)}

Lütfen şunları sun (TÜM YANITLAR TÜRKÇE OLMALI):
1. Genel İzleyici Duygu Özeti (2-3 cümle)
2. İzleyicilerin en çok sevdiği şeyler
3. İzleyicilerin beğenmediği şeyler
4. İzleyicilerden gelen önemli öneriler
5. Cevaplanması gereken yaygın sorular
6. İçerik üretici için uygulanabilir tavsiyeler

ÖNEMLİ: Tüm analizi TÜRKÇE yaz. İngilizce kullanma.
PROMPT;
            $systemPrompt = 'Sen bir YouTube izleyici analistisin. Yorum analizine dayalı net, uygulanabilir öneriler sun. MUTLAKA tüm yanıtlarını Türkçe olarak ver.';
        } else {
            $prompt = <<<PROMPT
Summarize the audience feedback for this video:

Video Title: {$videoData['title']}

Comment Statistics:
- Total Comments Analyzed: {$totalComments}
- Positive: {$counts['positive']}
- Supportive: {$counts['supportive']}
- Negative: {$counts['negative']}
- Criticism: {$counts['criticism']}
- Suggestions: {$counts['suggestion']}
- Questions: {$counts['question']}
- Toxic: {$counts['toxic']}
- Neutral: {$counts['neutral']}

Top Suggestions from viewers:
{$this->formatList($topSuggestions)}

Top Criticisms from viewers:
{$this->formatList($topCriticisms)}

Please provide:
1. Overall Audience Sentiment Summary (2-3 sentences)
2. What viewers loved most
3. What viewers didn't like
4. Key suggestions from the audience
5. Common questions that should be addressed
6. Actionable recommendations for the creator
PROMPT;
            $systemPrompt = 'You are a YouTube audience analyst. Provide clear, actionable insights based on comment analysis.';
        }

        $aiSummary = $this->ai->analyze($prompt, [
            'system_prompt' => $systemPrompt,
        ]);

        return [
            'counts' => $counts,
            'total' => $totalComments,
            'positive_ratio' => $positiveRatio,
            'negative_ratio' => 100 - $positiveRatio,
            'top_suggestions' => $topSuggestions,
            'top_criticisms' => $topCriticisms,
            'ai_summary' => $aiSummary,
            'model_used' => $this->ai->getModelName(),
        ];
    }

    /**
     * Format list for prompt.
     */
    protected function formatList(array $items): string
    {
        if (empty($items)) {
            return '- None';
        }

        return collect($items)
            ->map(fn($item) => '- ' . mb_substr($item, 0, 200))
            ->implode("\n");
    }

    /**
     * Save video to database.
     */
    protected function saveVideo(User $user, array $videoData): Video
    {
        $channel = \App\Models\Channel::firstOrCreate(
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
                'published_at' => $videoData['published_at'],
            ]
        );
    }

    /**
     * Save analysis to database.
     */
    protected function saveAnalysis(User $user, Video $video, array $categorizedComments, array $summary): CommentAnalysis
    {
        return CommentAnalysis::create([
            'video_id' => $video->id,
            'user_id' => $user->id,
            'total_comments' => $summary['total'],
            'positive_count' => $summary['counts']['positive'],
            'negative_count' => $summary['counts']['negative'],
            'supportive_count' => $summary['counts']['supportive'],
            'criticism_count' => $summary['counts']['criticism'],
            'suggestion_count' => $summary['counts']['suggestion'],
            'question_count' => $summary['counts']['question'],
            'toxic_count' => $summary['counts']['toxic'],
            'top_suggestions' => $summary['top_suggestions'],
            'top_criticisms' => $summary['top_criticisms'],
            'ai_summary' => $summary['ai_summary'],
            'model_used' => $summary['model_used'],
        ]);
    }
}
