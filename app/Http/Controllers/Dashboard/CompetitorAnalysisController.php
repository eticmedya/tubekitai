<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AIGeneration;
use App\Services\Credit\CreditManager;
use App\Services\AI\AIManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompetitorAnalysisController extends Controller
{
    public function __construct(
        protected CreditManager $creditManager,
        protected AIManager $aiManager
    ) {}

    /**
     * Display the competitor analysis page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get previous analyses
        $previousAnalyses = AIGeneration::where('user_id', $user->id)
            ->where('type', 'competitor')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('dashboard.competitor-analysis', compact('previousAnalyses'));
    }

    /**
     * Analyze competitors.
     */
    public function analyze(Request $request): JsonResponse
    {
        $request->validate([
            'my_channel' => ['required', 'string', 'max:500'],
            'competitor_channels' => ['required', 'array', 'min:1', 'max:5'],
            'competitor_channels.*' => ['required', 'string', 'max:500'],
        ]);

        $user = $request->user();
        $operation = 'competitor_analysis';

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
            // Build analysis prompt
            $competitorList = implode("\n", array_map(fn($c, $i) => ($i + 1) . ". " . $c, $request->competitor_channels, array_keys($request->competitor_channels)));

            $prompt = "YouTube rakip analizi yap. Benim kanalim: {$request->my_channel}\n\nRakip kanallar:\n{$competitorList}\n\n" .
                "Lutfen su basliklarda detayli analiz yap:\n" .
                "1. Rakiplerin Genel Degerlendirmesi (her rakip icin gucu zayiflik)\n" .
                "2. Icerik Stratejisi Karsilastirmasi\n" .
                "3. Baslik ve Thumbnail Analizi\n" .
                "4. Yukleme Sikligi ve Zamanlama\n" .
                "5. Izleyici Kitesi Analizi\n" .
                "6. Benim Kanalim icin Oneriler\n" .
                "7. Rakiplerden Ogrenilebilecek Taktikler\n" .
                "8. Rekabet Avantaji Olusturma Stratejileri\n\n" .
                "Lutfen Turkce ve detayli yanit ver. HTML formatinda don.";

            $content = $this->aiManager->generateText($prompt);
            $usage = $this->aiManager->getLastUsage();
            $model = $this->aiManager->getModelName();

            // Save to database
            $generation = AIGeneration::create([
                'user_id' => $user->id,
                'type' => 'competitor',
                'prompt' => $prompt,
                'context' => [
                    'my_channel' => $request->my_channel,
                    'competitors' => $request->competitor_channels,
                ],
                'result' => $content,
                'model_used' => $model,
                'input_tokens' => $usage['input_tokens'] ?? 0,
                'output_tokens' => $usage['output_tokens'] ?? 0,
                'credits_used' => $this->creditManager->getCost($operation),
            ]);

            // Deduct credits
            $this->creditManager->deduct(
                $user,
                $operation,
                __('credits.used_for', ['feature' => __('modules.competitor_analysis')]),
                $generation
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $generation->id,
                    'analysis' => $content,
                    'my_channel' => $request->my_channel,
                    'competitors' => $request->competitor_channels,
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
