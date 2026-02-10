<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\Credit\CreditManager;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected CreditManager $creditManager
    ) {}

    /**
     * Display the dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get recent activities from ActivityLog with pagination
        $recentActivities = ActivityLog::forUser($user->id)
            ->whereIn('action', [
                'channel_analysis',
                'video_analysis',
                'comment_analysis',
                'cover_analysis',
                'cover_generation',
                'niche_analysis',
                'translation',
                'ai_generation',
            ])
            ->with('subject')
            ->latest('created_at')
            ->paginate(10);

        // Get usage stats
        $usageStats = $this->creditManager->getUsageStats($user, 30);

        // Get modules data
        $modules = $this->getModulesData();

        return view('dashboard.index', compact(
            'user',
            'recentActivities',
            'usageStats',
            'modules'
        ));
    }

    /**
     * Get modules data for dashboard cards.
     */
    protected function getModulesData(): array
    {
        return [
            [
                'name' => 'channel_analysis',
                'title' => __('modules.channel_analysis'),
                'description' => __('modules.channel_analysis_desc'),
                'icon' => 'chart-bar',
                'route' => 'channel-analysis',
                'cost' => config('credits.costs.channel_analysis'),
                'color' => 'blue',
            ],
            [
                'name' => 'video_analysis',
                'title' => __('modules.video_analysis'),
                'description' => __('modules.video_analysis_desc'),
                'icon' => 'play-circle',
                'route' => 'video-analysis',
                'cost' => config('credits.costs.channel_analysis'),
                'color' => 'red',
            ],
            [
                'name' => 'comment_analysis',
                'title' => __('modules.comment_analysis'),
                'description' => __('modules.comment_analysis_desc'),
                'icon' => 'chat-bubble-left-right',
                'route' => 'comment-analysis',
                'cost' => config('credits.costs.comment_analysis'),
                'color' => 'green',
            ],
            [
                'name' => 'cover_ai',
                'title' => __('modules.cover_ai'),
                'description' => __('modules.cover_ai_desc'),
                'icon' => 'photo',
                'route' => 'cover-ai',
                'cost' => config('credits.costs.cover_analysis_score'),
                'color' => 'purple',
            ],
            [
                'name' => 'niche_analysis',
                'title' => __('modules.niche_analysis'),
                'description' => __('modules.niche_analysis_desc'),
                'icon' => 'light-bulb',
                'route' => 'niche-analysis',
                'cost' => config('credits.costs.niche_analysis_detailed'),
                'color' => 'yellow',
            ],
            [
                'name' => 'video_ideas',
                'title' => __('modules.video_ideas'),
                'description' => __('modules.video_ideas_desc'),
                'icon' => 'sparkles',
                'route' => 'idea-generator',
                'cost' => config('credits.costs.video_idea_generation'),
                'color' => 'pink',
            ],
            [
                'name' => 'trend_discovery',
                'title' => __('modules.trend_discovery'),
                'description' => __('modules.trend_discovery_desc'),
                'icon' => 'arrow-trending-up',
                'route' => 'trend-discovery',
                'cost' => config('credits.costs.trend_discovery'),
                'color' => 'orange',
            ],
            [
                'name' => 'competitor_analysis',
                'title' => __('modules.competitor_analysis'),
                'description' => __('modules.competitor_analysis_desc'),
                'icon' => 'users',
                'route' => 'competitor-analysis',
                'cost' => config('credits.costs.competitor_analysis'),
                'color' => 'indigo',
            ],
            [
                'name' => 'keyword_trends',
                'title' => __('modules.keyword_trends'),
                'description' => __('modules.keyword_trends_desc'),
                'icon' => 'magnifying-glass',
                'route' => 'keyword-trends',
                'cost' => config('credits.costs.keyword_analysis'),
                'color' => 'teal',
            ],
            [
                'name' => 'transflow',
                'title' => __('modules.transflow'),
                'description' => __('modules.transflow_desc'),
                'icon' => 'language',
                'route' => 'transflow',
                'cost' => config('credits.costs.transflow_subtitle'),
                'color' => 'cyan',
            ],
            [
                'name' => 'creator_school',
                'title' => __('modules.creator_school'),
                'description' => __('modules.creator_school_desc'),
                'icon' => 'academic-cap',
                'route' => 'creator-school',
                'cost' => 0,
                'color' => 'emerald',
                'badge' => __('app.free'),
            ],
        ];
    }
}
