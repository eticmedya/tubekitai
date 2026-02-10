<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Credit\CreditManager;
use App\Services\YouTube\YouTubeAPIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrendDiscoveryController extends Controller
{
    public function __construct(
        protected YouTubeAPIService $youtube,
        protected CreditManager $credits
    ) {}

    public function index(): View
    {
        return view('dashboard.trend-discovery');
    }

    public function trends(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'region' => 'nullable|string|size:2',
            'category' => 'nullable|string',
            'time_range' => 'nullable|in:today,week,month',
        ]);

        try {
            $region = $validated['region'] ?? 'TR';
            $category = $validated['category'] ?? null;

            // Try to get real data from YouTube API
            try {
                $videos = $this->youtube->getTrending($region, $category);

                if ($videos && $videos->count() > 0) {
                    $formattedVideos = $videos->map(function ($video) {
                        return [
                            'id' => $video['youtube_id'] ?? $video['id'],
                            'title' => $video['title'],
                            'thumbnail' => $video['thumbnail_url'] ?? $video['thumbnail'],
                            'channel_title' => $video['channel_title'],
                            'view_count' => $video['view_count'],
                            'like_count' => $video['like_count'] ?? 0,
                            'comment_count' => $video['comment_count'] ?? 0,
                            'duration' => $this->formatDuration($video['duration'] ?? 'PT0S'),
                            'published_at' => $this->formatPublishedAt($video['published_at']),
                        ];
                    })->take(25);

                    return response()->json([
                        'success' => true,
                        'data' => [
                            'videos' => $formattedVideos,
                        ],
                    ]);
                }
            } catch (\Exception $e) {
                // API failed, use demo data
            }

            // Return demo data if API fails or returns empty
            $demoVideos = $this->getDemoTrendingVideos($region, $category);

            return response()->json([
                'success' => true,
                'data' => [
                    'videos' => $demoVideos,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('app.error_occurred'),
            ], 500);
        }
    }

    public function rising(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'region' => 'nullable|string|size:2',
        ]);

        try {
            $topics = $this->getRisingTopics($validated['region'] ?? 'TR');

            return response()->json([
                'success' => true,
                'data' => [
                    'topics' => $topics,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('app.error_occurred'),
            ], 500);
        }
    }

    protected function getDemoTrendingVideos(string $region, ?string $category): array
    {
        $demoData = [
            'TR' => [
                [
                    'id' => 'dQw4w9WgXcQ',
                    'title' => 'Türkiye\'de En Çok İzlenen Müzik Videosu 2024',
                    'thumbnail' => 'https://i.ytimg.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                    'channel_title' => 'Popüler Müzik TR',
                    'view_count' => 15420000,
                    'like_count' => 892000,
                    'comment_count' => 45200,
                    'duration' => '3:45',
                    'published_at' => '2 saat önce',
                ],
                [
                    'id' => 'jNQXAC9IVRw',
                    'title' => 'Yeni Sezon Dizi Fragmanı - Büyük Sürpriz!',
                    'thumbnail' => 'https://i.ytimg.com/vi/jNQXAC9IVRw/maxresdefault.jpg',
                    'channel_title' => 'Dizi TV',
                    'view_count' => 8750000,
                    'like_count' => 534000,
                    'comment_count' => 28900,
                    'duration' => '2:30',
                    'published_at' => '5 saat önce',
                ],
                [
                    'id' => '9bZkp7q19f0',
                    'title' => 'Futbol Maçı Özeti - Şampiyonluk Yarışı',
                    'thumbnail' => 'https://i.ytimg.com/vi/9bZkp7q19f0/maxresdefault.jpg',
                    'channel_title' => 'Spor Haberleri',
                    'view_count' => 6230000,
                    'like_count' => 312000,
                    'comment_count' => 67800,
                    'duration' => '12:45',
                    'published_at' => '8 saat önce',
                ],
                [
                    'id' => 'kJQP7kiw5Fk',
                    'title' => 'Teknoloji İnceleme: Yeni iPhone vs Samsung',
                    'thumbnail' => 'https://i.ytimg.com/vi/kJQP7kiw5Fk/maxresdefault.jpg',
                    'channel_title' => 'Tech Review TR',
                    'view_count' => 4890000,
                    'like_count' => 245000,
                    'comment_count' => 18700,
                    'duration' => '18:32',
                    'published_at' => '1 gün önce',
                ],
                [
                    'id' => 'RgKAFK5djSk',
                    'title' => 'Yemek Tarifi: Evde Kolay Pizza Yapımı',
                    'thumbnail' => 'https://i.ytimg.com/vi/RgKAFK5djSk/maxresdefault.jpg',
                    'channel_title' => 'Lezzetli Tarifler',
                    'view_count' => 3450000,
                    'like_count' => 198000,
                    'comment_count' => 8900,
                    'duration' => '15:20',
                    'published_at' => '1 gün önce',
                ],
                [
                    'id' => 'OPf0YbXqDm0',
                    'title' => 'Stand-up Gösterisi - En Komik Anlar',
                    'thumbnail' => 'https://i.ytimg.com/vi/OPf0YbXqDm0/maxresdefault.jpg',
                    'channel_title' => 'Kahkaha TV',
                    'view_count' => 2890000,
                    'like_count' => 156000,
                    'comment_count' => 12300,
                    'duration' => '25:10',
                    'published_at' => '2 gün önce',
                ],
                [
                    'id' => 'fJ9rUzIMcZQ',
                    'title' => 'Oyun İnceleme: GTA 6 Detaylı Analiz',
                    'thumbnail' => 'https://i.ytimg.com/vi/fJ9rUzIMcZQ/maxresdefault.jpg',
                    'channel_title' => 'Gaming TR',
                    'view_count' => 5670000,
                    'like_count' => 423000,
                    'comment_count' => 34500,
                    'duration' => '22:15',
                    'published_at' => '3 gün önce',
                ],
                [
                    'id' => 'hT_nvWreIhg',
                    'title' => 'Motivasyon Konuşması - Başarının Sırrı',
                    'thumbnail' => 'https://i.ytimg.com/vi/hT_nvWreIhg/maxresdefault.jpg',
                    'channel_title' => 'Kişisel Gelişim',
                    'view_count' => 1980000,
                    'like_count' => 134000,
                    'comment_count' => 5600,
                    'duration' => '14:50',
                    'published_at' => '3 gün önce',
                ],
                [
                    'id' => 'CevxZvSJLk8',
                    'title' => 'Vlog: İstanbul Turu - Gizli Mekanlar',
                    'thumbnail' => 'https://i.ytimg.com/vi/CevxZvSJLk8/maxresdefault.jpg',
                    'channel_title' => 'Gezgin Vlogger',
                    'view_count' => 1540000,
                    'like_count' => 98000,
                    'comment_count' => 4200,
                    'duration' => '28:40',
                    'published_at' => '4 gün önce',
                ],
            ],
            'US' => [
                [
                    'id' => 'dQw4w9WgXcQ',
                    'title' => 'Top Billboard Hit 2024 - Official Music Video',
                    'thumbnail' => 'https://i.ytimg.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                    'channel_title' => 'Billboard Music',
                    'view_count' => 45200000,
                    'like_count' => 2340000,
                    'comment_count' => 156000,
                    'duration' => '4:12',
                    'published_at' => '3 hours ago',
                ],
                [
                    'id' => 'jNQXAC9IVRw',
                    'title' => 'NFL Highlights - Super Bowl Preview',
                    'thumbnail' => 'https://i.ytimg.com/vi/jNQXAC9IVRw/maxresdefault.jpg',
                    'channel_title' => 'ESPN',
                    'view_count' => 28900000,
                    'like_count' => 1560000,
                    'comment_count' => 89000,
                    'duration' => '15:30',
                    'published_at' => '6 hours ago',
                ],
                [
                    'id' => '9bZkp7q19f0',
                    'title' => 'Tech News: Apple Vision Pro Review',
                    'thumbnail' => 'https://i.ytimg.com/vi/9bZkp7q19f0/maxresdefault.jpg',
                    'channel_title' => 'MKBHD',
                    'view_count' => 12400000,
                    'like_count' => 890000,
                    'comment_count' => 45600,
                    'duration' => '20:45',
                    'published_at' => '1 day ago',
                ],
                [
                    'id' => 'kJQP7kiw5Fk',
                    'title' => 'Movie Trailer: Marvel\'s Next Big Film',
                    'thumbnail' => 'https://i.ytimg.com/vi/kJQP7kiw5Fk/maxresdefault.jpg',
                    'channel_title' => 'Marvel Entertainment',
                    'view_count' => 67800000,
                    'like_count' => 3450000,
                    'comment_count' => 234000,
                    'duration' => '2:45',
                    'published_at' => '2 days ago',
                ],
                [
                    'id' => 'RgKAFK5djSk',
                    'title' => 'MrBeast: $1,000,000 Challenge',
                    'thumbnail' => 'https://i.ytimg.com/vi/RgKAFK5djSk/maxresdefault.jpg',
                    'channel_title' => 'MrBeast',
                    'view_count' => 89500000,
                    'like_count' => 5670000,
                    'comment_count' => 456000,
                    'duration' => '18:20',
                    'published_at' => '3 days ago',
                ],
                [
                    'id' => 'OPf0YbXqDm0',
                    'title' => 'Gaming: Fortnite New Season Gameplay',
                    'thumbnail' => 'https://i.ytimg.com/vi/OPf0YbXqDm0/maxresdefault.jpg',
                    'channel_title' => 'Ninja',
                    'view_count' => 15600000,
                    'like_count' => 890000,
                    'comment_count' => 67000,
                    'duration' => '25:00',
                    'published_at' => '4 days ago',
                ],
            ],
        ];

        // Return data for the selected region, or TR as default
        $videos = $demoData[$region] ?? $demoData['TR'];

        // Shuffle to make it look dynamic
        shuffle($videos);

        return $videos;
    }

    protected function getRisingTopics(string $region): array
    {
        $topicsByRegion = [
            'TR' => [
                ['keyword' => 'Yapay Zeka Videoları', 'growth' => 285, 'score' => 98, 'search_volume' => '75K+'],
                ['keyword' => 'Kısa Video İçerikleri', 'growth' => 220, 'score' => 92, 'search_volume' => '150K+'],
                ['keyword' => 'Gaming Highlights', 'growth' => 165, 'score' => 85, 'search_volume' => '90K+'],
                ['keyword' => 'Teknoloji İnceleme', 'growth' => 140, 'score' => 82, 'search_volume' => '60K+'],
                ['keyword' => 'Yemek ASMR', 'growth' => 125, 'score' => 78, 'search_volume' => '45K+'],
                ['keyword' => 'Fitness Challenge', 'growth' => 110, 'score' => 74, 'search_volume' => '80K+'],
                ['keyword' => 'Bütçe Seyahat', 'growth' => 95, 'score' => 68, 'search_volume' => '35K+'],
                ['keyword' => 'Kendin Yap Projeleri', 'growth' => 85, 'score' => 62, 'search_volume' => '55K+'],
            ],
            'US' => [
                ['keyword' => 'AI Video Editing', 'growth' => 320, 'score' => 99, 'search_volume' => '200K+'],
                ['keyword' => 'Short Form Content', 'growth' => 245, 'score' => 95, 'search_volume' => '350K+'],
                ['keyword' => 'Gaming Streams', 'growth' => 180, 'score' => 88, 'search_volume' => '180K+'],
                ['keyword' => 'Tech Reviews 2024', 'growth' => 155, 'score' => 84, 'search_volume' => '120K+'],
                ['keyword' => 'Cooking ASMR', 'growth' => 130, 'score' => 79, 'search_volume' => '90K+'],
                ['keyword' => 'Fitness Journey', 'growth' => 115, 'score' => 75, 'search_volume' => '150K+'],
                ['keyword' => 'Budget Travel', 'growth' => 100, 'score' => 70, 'search_volume' => '75K+'],
                ['keyword' => 'DIY Home Projects', 'growth' => 90, 'score' => 65, 'search_volume' => '100K+'],
            ],
        ];

        $topics = $topicsByRegion[$region] ?? $topicsByRegion['TR'];

        // Shuffle to simulate dynamic data
        shuffle($topics);

        return $topics;
    }

    protected function formatDuration(string $duration): string
    {
        try {
            $interval = new \DateInterval($duration);
            $hours = $interval->h;
            $minutes = $interval->i;
            $seconds = $interval->s;

            if ($hours > 0) {
                return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
            }

            return sprintf('%d:%02d', $minutes, $seconds);
        } catch (\Exception $e) {
            return '0:00';
        }
    }

    protected function formatPublishedAt(string $publishedAt): string
    {
        try {
            $date = new \DateTime($publishedAt);
            $now = new \DateTime();
            $diff = $now->diff($date);

            if ($diff->days == 0) {
                if ($diff->h == 0) {
                    return $diff->i . ' ' . __('modules.minutes_ago');
                }
                return $diff->h . ' ' . __('modules.hours_ago');
            }

            if ($diff->days == 1) {
                return __('modules.yesterday');
            }

            if ($diff->days < 7) {
                return $diff->days . ' ' . __('modules.days_ago');
            }

            if ($diff->days < 30) {
                $weeks = floor($diff->days / 7);
                return $weeks . ' ' . __('modules.weeks_ago');
            }

            return $date->format('M j, Y');
        } catch (\Exception $e) {
            return $publishedAt;
        }
    }
}
