<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\Credit\CreditManager;
use App\Services\YouTube\ChannelAnalyzer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChannelAnalysisController extends Controller
{
    public function __construct(
        protected ChannelAnalyzer $analyzer,
        protected CreditManager $creditManager
    ) {}

    /**
     * Display the channel analysis page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get previous analyses
        $previousAnalyses = $user->channels()
            ->whereNotNull('analyzed_at')
            ->orderByDesc('analyzed_at')
            ->take(10)
            ->get();

        return view('dashboard.channel-analysis', compact('previousAnalyses'));
    }

    /**
     * Analyze a channel.
     */
    public function analyze(Request $request): JsonResponse
    {
        $request->validate([
            'channel_url' => ['required', 'string', 'max:500'],
        ]);

        $user = $request->user();
        $operation = 'channel_analysis';

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
            $result = $this->analyzer->analyze($request->channel_url, $user);

            // Deduct credits
            $this->creditManager->deduct(
                $user,
                $operation,
                __('credits.used_for', ['feature' => __('modules.channel_analysis')]),
                $result['channel']
            );

            // Log activity
            ActivityLog::log('channel_analysis', $result['channel']);

            return response()->json([
                'success' => true,
                'data' => [
                    'channel' => $result['channel'],
                    'metrics' => $result['metrics'],
                    'top_videos' => $result['top_videos'],
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

    /**
     * Show a previous analysis.
     */
    public function show(Request $request, int $id): View
    {
        $channel = $request->user()->channels()->with('videos')->findOrFail($id);

        return view('dashboard.channel-analysis-result', compact('channel'));
    }
}
