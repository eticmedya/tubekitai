<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\Credit\CreditManager;
use App\Services\YouTube\CommentAnalyzer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentAnalysisController extends Controller
{
    public function __construct(
        protected CommentAnalyzer $analyzer,
        protected CreditManager $creditManager
    ) {}

    /**
     * Display the comment analysis page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get previous analyses
        $previousAnalyses = $user->commentAnalyses()
            ->with('video')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('dashboard.comment-analysis', compact('previousAnalyses'));
    }

    /**
     * Analyze comments.
     */
    public function analyze(Request $request): JsonResponse
    {
        $request->validate([
            'video_url' => ['required', 'string', 'max:500'],
            'limit' => ['nullable', 'integer', 'min:10', 'max:500'],
        ]);

        $user = $request->user();
        $operation = 'comment_analysis';

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
            $result = $this->analyzer->analyze(
                $request->video_url,
                $user,
                $request->input('limit', 100)
            );

            // Deduct credits
            $this->creditManager->deduct(
                $user,
                $operation,
                __('credits.used_for', ['feature' => __('modules.comment_analysis')]),
                $result['analysis']
            );

            // Update analysis with credits used
            $result['analysis']->update([
                'credits_used' => $this->creditManager->getCost($operation),
            ]);

            // Log activity
            ActivityLog::log('comment_analysis', $result['analysis']);

            return response()->json([
                'success' => true,
                'data' => [
                    'video' => $result['video'],
                    'analysis' => $result['analysis'],
                    'summary' => $result['summary'],
                    'comments' => $result['comments'],
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
        $analysis = $request->user()->commentAnalyses()->with('video')->findOrFail($id);

        return view('dashboard.comment-analysis-result', compact('analysis'));
    }
}
