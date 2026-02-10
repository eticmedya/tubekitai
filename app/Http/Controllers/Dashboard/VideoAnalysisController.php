<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Video;
use App\Services\Credit\CreditManager;
use App\Services\YouTube\VideoAnalyzer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoAnalysisController extends Controller
{
    public function __construct(
        protected VideoAnalyzer $analyzer,
        protected CreditManager $creditManager
    ) {}

    /**
     * Display the video analysis page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get previous analyses
        $previousAnalyses = Video::whereHas('channel', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->orderByDesc('updated_at')
            ->take(10)
            ->get();

        return view('dashboard.video-analysis', compact('previousAnalyses'));
    }

    /**
     * Analyze a video.
     */
    public function analyze(Request $request): JsonResponse
    {
        $request->validate([
            'video_url' => ['required', 'string', 'max:500'],
        ]);

        $user = $request->user();
        $operation = 'channel_analysis'; // Same cost as channel analysis

        // Check credits
        if (!$this->creditManager->hasEnough($user, $operation)) {
            return response()->json([
                'success' => false,
                'error' => __('credits.insufficient'),
                'required' => $this->creditManager->getCost($operation),
                'available' => $user->credits,
            ], 402);
        }

        try {
            $result = $this->analyzer->analyze($request->video_url, $user);

            // Deduct credits
            $this->creditManager->deduct(
                $user,
                $operation,
                __('credits.used_for', ['feature' => __('modules.video_analysis')]),
                $result['video']
            );

            // Log activity
            ActivityLog::log('video_analysis', $result['video']);

            return response()->json([
                'success' => true,
                'data' => [
                    'video' => $result['video'],
                    'metrics' => $result['metrics'],
                    'ai_analysis' => $result['ai_analysis'],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
