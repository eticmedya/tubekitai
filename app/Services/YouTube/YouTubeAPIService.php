<?php

namespace App\Services\YouTube;

use App\Models\Channel;
use App\Models\Video;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouTubeAPIService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct(?string $apiKey = null)
    {
        $this->apiKey = $apiKey ?? config('services.youtube.api_key');
        $this->baseUrl = config('services.youtube.base_url', 'https://www.googleapis.com/youtube/v3');
    }

    /**
     * Get channel by URL or ID.
     */
    public function getChannel(string $urlOrId): ?array
    {
        $channelId = $this->extractChannelId($urlOrId);

        if (!$channelId) {
            return null;
        }

        $cacheKey = "youtube_channel:{$channelId}";

        return Cache::remember($cacheKey, 3600, function () use ($channelId) {
            $response = $this->request('channels', [
                'part' => 'snippet,statistics,brandingSettings',
                'id' => $channelId,
            ]);

            if (empty($response['items'])) {
                return null;
            }

            $item = $response['items'][0];

            return [
                'youtube_id' => $item['id'],
                'title' => $item['snippet']['title'],
                'description' => $item['snippet']['description'] ?? '',
                'thumbnail_url' => $item['snippet']['thumbnails']['high']['url'] ?? null,
                'custom_url' => $item['snippet']['customUrl'] ?? null,
                'country' => $item['snippet']['country'] ?? null,
                'published_at' => $item['snippet']['publishedAt'] ?? null,
                'subscriber_count' => (int) ($item['statistics']['subscriberCount'] ?? 0),
                'video_count' => (int) ($item['statistics']['videoCount'] ?? 0),
                'view_count' => (int) ($item['statistics']['viewCount'] ?? 0),
            ];
        });
    }

    /**
     * Get channel by username or handle.
     */
    public function getChannelByHandle(string $handle): ?array
    {
        $handle = ltrim($handle, '@');

        $response = $this->request('channels', [
            'part' => 'snippet,statistics,brandingSettings',
            'forHandle' => $handle,
        ]);

        if (empty($response['items'])) {
            // Try as username
            $response = $this->request('channels', [
                'part' => 'snippet,statistics,brandingSettings',
                'forUsername' => $handle,
            ]);
        }

        if (empty($response['items'])) {
            return null;
        }

        $item = $response['items'][0];

        return [
            'youtube_id' => $item['id'],
            'title' => $item['snippet']['title'],
            'description' => $item['snippet']['description'] ?? '',
            'thumbnail_url' => $item['snippet']['thumbnails']['high']['url'] ?? null,
            'custom_url' => $item['snippet']['customUrl'] ?? null,
            'country' => $item['snippet']['country'] ?? null,
            'published_at' => $item['snippet']['publishedAt'] ?? null,
            'subscriber_count' => (int) ($item['statistics']['subscriberCount'] ?? 0),
            'video_count' => (int) ($item['statistics']['videoCount'] ?? 0),
            'view_count' => (int) ($item['statistics']['viewCount'] ?? 0),
        ];
    }

    /**
     * Get videos from channel.
     */
    public function getChannelVideos(string $channelId, int $limit = 50): Collection
    {
        $videos = collect();
        $pageToken = null;
        $remaining = $limit;

        while ($remaining > 0) {
            $maxResults = min($remaining, 50);

            $response = $this->request('search', [
                'part' => 'snippet',
                'channelId' => $channelId,
                'type' => 'video',
                'order' => 'date',
                'maxResults' => $maxResults,
                'pageToken' => $pageToken,
            ]);

            if (empty($response['items'])) {
                break;
            }

            $videoIds = collect($response['items'])->pluck('id.videoId')->filter()->toArray();

            if (!empty($videoIds)) {
                $videoDetails = $this->getVideosDetails($videoIds);
                $videos = $videos->merge($videoDetails);
            }

            $pageToken = $response['nextPageToken'] ?? null;
            $remaining -= count($response['items']);

            if (!$pageToken) {
                break;
            }
        }

        return $videos;
    }

    /**
     * Get video details.
     */
    public function getVideo(string $videoId): ?array
    {
        $cacheKey = "youtube_video:{$videoId}";

        return Cache::remember($cacheKey, 1800, function () use ($videoId) {
            $response = $this->request('videos', [
                'part' => 'snippet,statistics,contentDetails',
                'id' => $videoId,
            ]);

            if (empty($response['items'])) {
                return null;
            }

            return $this->parseVideoItem($response['items'][0]);
        });
    }

    /**
     * Get multiple videos details.
     */
    public function getVideosDetails(array $videoIds): Collection
    {
        $response = $this->request('videos', [
            'part' => 'snippet,statistics,contentDetails',
            'id' => implode(',', $videoIds),
        ]);

        if (empty($response['items'])) {
            return collect();
        }

        return collect($response['items'])->map(fn($item) => $this->parseVideoItem($item));
    }

    /**
     * Get video comments.
     */
    public function getVideoComments(string $videoId, int $limit = 100): Collection
    {
        $comments = collect();
        $pageToken = null;
        $remaining = $limit;

        while ($remaining > 0) {
            $maxResults = min($remaining, 100);

            try {
                $response = $this->request('commentThreads', [
                    'part' => 'snippet',
                    'videoId' => $videoId,
                    'order' => 'relevance',
                    'maxResults' => $maxResults,
                    'pageToken' => $pageToken,
                ]);
            } catch (\Exception $e) {
                // Comments might be disabled
                Log::warning('Failed to fetch comments', [
                    'video_id' => $videoId,
                    'error' => $e->getMessage(),
                ]);
                break;
            }

            if (empty($response['items'])) {
                break;
            }

            foreach ($response['items'] as $item) {
                $comment = $item['snippet']['topLevelComment']['snippet'];
                $comments->push([
                    'youtube_id' => $item['id'],
                    'author' => $comment['authorDisplayName'],
                    'author_channel_id' => $comment['authorChannelId']['value'] ?? null,
                    'text' => $comment['textDisplay'],
                    'like_count' => (int) ($comment['likeCount'] ?? 0),
                    'reply_count' => (int) ($item['snippet']['totalReplyCount'] ?? 0),
                    'published_at' => $comment['publishedAt'],
                ]);
            }

            $pageToken = $response['nextPageToken'] ?? null;
            $remaining -= count($response['items']);

            if (!$pageToken) {
                break;
            }
        }

        return $comments;
    }

    /**
     * Search for trending videos.
     */
    public function getTrending(string $region = 'TR', string $category = null): Collection
    {
        $params = [
            'part' => 'snippet,statistics',
            'chart' => 'mostPopular',
            'regionCode' => $region,
            'maxResults' => 50,
        ];

        if ($category) {
            $params['videoCategoryId'] = $category;
        }

        $response = $this->request('videos', $params);

        if (empty($response['items'])) {
            return collect();
        }

        return collect($response['items'])->map(fn($item) => $this->parseVideoItem($item));
    }

    /**
     * Search videos.
     */
    public function search(string $query, int $limit = 25, string $type = 'video'): Collection
    {
        $response = $this->request('search', [
            'part' => 'snippet',
            'q' => $query,
            'type' => $type,
            'maxResults' => min($limit, 50),
            'order' => 'relevance',
        ]);

        if (empty($response['items'])) {
            return collect();
        }

        if ($type === 'video') {
            $videoIds = collect($response['items'])->pluck('id.videoId')->filter()->toArray();
            if (!empty($videoIds)) {
                return $this->getVideosDetails($videoIds);
            }
        }

        return collect($response['items'])->map(function ($item) {
            return [
                'id' => $item['id']['channelId'] ?? $item['id']['videoId'] ?? null,
                'title' => $item['snippet']['title'],
                'description' => $item['snippet']['description'],
                'thumbnail_url' => $item['snippet']['thumbnails']['high']['url'] ?? null,
                'published_at' => $item['snippet']['publishedAt'],
            ];
        });
    }

    /**
     * Extract channel ID from URL.
     */
    public function extractChannelId(string $urlOrId): ?string
    {
        // Already a channel ID
        if (preg_match('/^UC[\w-]{22}$/', $urlOrId)) {
            return $urlOrId;
        }

        // Channel URL patterns
        $patterns = [
            '/youtube\.com\/channel\/(UC[\w-]{22})/',
            '/youtube\.com\/c\/([\w-]+)/',
            '/youtube\.com\/@([\w-]+)/',
            '/youtube\.com\/user\/([\w-]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $urlOrId, $matches)) {
                $identifier = $matches[1];

                // If it's a channel ID, return it
                if (preg_match('/^UC[\w-]{22}$/', $identifier)) {
                    return $identifier;
                }

                // Otherwise, look up by handle/username
                $channel = $this->getChannelByHandle($identifier);
                return $channel['youtube_id'] ?? null;
            }
        }

        // Try as handle directly
        if (str_starts_with($urlOrId, '@')) {
            $channel = $this->getChannelByHandle($urlOrId);
            return $channel['youtube_id'] ?? null;
        }

        return null;
    }

    /**
     * Extract video ID from URL.
     */
    public function extractVideoId(string $urlOrId): ?string
    {
        // Already a video ID
        if (preg_match('/^[\w-]{11}$/', $urlOrId)) {
            return $urlOrId;
        }

        $patterns = [
            '/youtube\.com\/watch\?v=([\w-]{11})/',
            '/youtu\.be\/([\w-]{11})/',
            '/youtube\.com\/embed\/([\w-]{11})/',
            '/youtube\.com\/v\/([\w-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $urlOrId, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Parse video item from API response.
     */
    protected function parseVideoItem(array $item): array
    {
        $duration = $item['contentDetails']['duration'] ?? 'PT0S';

        return [
            'youtube_id' => $item['id'],
            'title' => $item['snippet']['title'],
            'description' => $item['snippet']['description'] ?? '',
            'thumbnail_url' => $item['snippet']['thumbnails']['high']['url'] ?? null,
            'channel_id' => $item['snippet']['channelId'] ?? null,
            'channel_title' => $item['snippet']['channelTitle'] ?? null,
            'published_at' => $item['snippet']['publishedAt'] ?? null,
            'tags' => $item['snippet']['tags'] ?? [],
            'category_id' => $item['snippet']['categoryId'] ?? null,
            'default_language' => $item['snippet']['defaultLanguage'] ?? null,
            'duration' => $duration,
            'duration_seconds' => $this->parseDuration($duration),
            'view_count' => (int) ($item['statistics']['viewCount'] ?? 0),
            'like_count' => (int) ($item['statistics']['likeCount'] ?? 0),
            'comment_count' => (int) ($item['statistics']['commentCount'] ?? 0),
        ];
    }

    /**
     * Parse ISO 8601 duration to seconds.
     */
    protected function parseDuration(string $duration): int
    {
        try {
            $interval = new \DateInterval($duration);
            return ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Make API request.
     */
    protected function request(string $endpoint, array $params = []): array
    {
        $params['key'] = $this->apiKey;

        // Local development için SSL doğrulamasını devre dışı bırak
        $http = Http::timeout(30);
        if (app()->environment('local')) {
            $http = $http->withOptions(['verify' => false]);
        }

        $response = $http->get("{$this->baseUrl}/{$endpoint}", $params);

        if ($response->failed()) {
            Log::error('YouTube API Error', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('YouTube API request failed: ' . $response->body());
        }

        return $response->json();
    }
}
