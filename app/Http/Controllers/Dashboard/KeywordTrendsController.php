<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AIGeneration;
use App\Services\Credit\CreditManager;
use App\Services\AI\AIManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KeywordTrendsController extends Controller
{
    public function __construct(
        protected CreditManager $creditManager,
        protected AIManager $aiManager
    ) {}

    /**
     * Display the keyword trends page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get previous searches
        $previousSearches = AIGeneration::where('user_id', $user->id)
            ->where('type', 'keyword')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('dashboard.keyword-trends', compact('previousSearches'));
    }

    /**
     * Analyze keyword trends.
     */
    public function analyze(Request $request): JsonResponse
    {
        $request->validate([
            'niche' => ['required', 'string', 'max:200'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'language' => ['nullable', 'string', 'max:10'],
        ]);

        $user = $request->user();
        $operation = 'keyword_trends';

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
            $keywords = $request->keywords ? "Belirli anahtar kelimeler: {$request->keywords}" : "";
            $language = $request->language ?? 'tr';

            $prompt = "YouTube icin '{$request->niche}' nisinde anahtar kelime trend analizi yap.\n{$keywords}\n\n" .
                "Lutfen su bilgileri JSON formatinda ver:\n" .
                "1. trending_keywords: Yukselen 10 anahtar kelime (her biri icin: keyword, search_volume_estimate (dusuk/orta/yuksek), competition (dusuk/orta/yuksek), trend_direction (yukseliyor/stabil/dusuyor), opportunity_score (1-100))\n" .
                "2. long_tail_keywords: 10 uzun kuyruk anahtar kelime onerisi\n" .
                "3. seasonal_keywords: Mevsimsel trendler (varsa)\n" .
                "4. question_keywords: Soru formatinda populer aramalar (5 adet)\n" .
                "5. content_ideas: Bu anahtar kelimelerle yapilabilecek 5 video fikri\n" .
                "6. seo_tips: SEO icin 5 ipucu\n\n" .
                "Dil: " . ($language === 'tr' ? 'Turkce' : 'English') . "\n" .
                "Sadece JSON formatinda yanit ver, baska bir sey yazma.";

            $content = $this->aiManager->generateText($prompt);
            $usage = $this->aiManager->getLastUsage();
            $model = $this->aiManager->getModelName();

            // Parse JSON from response
            $jsonStart = strpos($content, '{');
            $jsonEnd = strrpos($content, '}');
            if ($jsonStart !== false && $jsonEnd !== false) {
                $jsonStr = substr($content, $jsonStart, $jsonEnd - $jsonStart + 1);
                $parsedData = json_decode($jsonStr, true);
            } else {
                $parsedData = null;
            }

            // Save to database
            $generation = AIGeneration::create([
                'user_id' => $user->id,
                'type' => 'keyword',
                'prompt' => $prompt,
                'context' => [
                    'niche' => $request->niche,
                    'keywords' => $request->keywords,
                    'language' => $language,
                ],
                'result' => $content,
                'result_meta' => $parsedData,
                'model_used' => $model,
                'input_tokens' => $usage['input_tokens'] ?? 0,
                'output_tokens' => $usage['output_tokens'] ?? 0,
                'credits_used' => $this->creditManager->getCost($operation),
            ]);

            // Deduct credits
            $this->creditManager->deduct(
                $user,
                $operation,
                __('credits.used_for', ['feature' => __('modules.keyword_trends')])
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $generation->id,
                    'niche' => $request->niche,
                    'analysis' => $parsedData ?? $content,
                    'raw' => $content,
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
