<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\NicheAnalysis;
use App\Services\AI\AIManager;
use App\Services\Credit\CreditManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NicheAnalysisController extends Controller
{
    public function __construct(
        protected AIManager $ai,
        protected CreditManager $creditManager
    ) {}

    /**
     * Display the niche analysis page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get previous analyses
        $previousAnalyses = $user->nicheAnalyses()
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('dashboard.niche-analysis', compact('previousAnalyses'));
    }

    /**
     * Analyze niche potential.
     */
    public function analyze(Request $request): JsonResponse
    {
        $request->validate([
            'interests' => ['required', 'array', 'min:1'],
            'interests.*' => ['string', 'max:100'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['string', 'max:100'],
            'lifestyle' => ['nullable', 'string', 'max:100'],
            'time_availability' => ['required', 'string', 'in:minimal,moderate,significant,fulltime,very_limited,part_time,full_time,unlimited'],
            'target_audience' => ['nullable', 'string', 'max:500'],
            'content_language' => ['nullable', 'string', 'max:10'],
        ]);

        $user = $request->user();
        $operation = 'niche_analysis_detailed';

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
            $interests = implode(', ', $request->interests);
            $skills = implode(', ', $request->skills ?? []);
            $lifestyle = $request->lifestyle ?? 'not specified';
            $timeAvailability = $request->time_availability;
            $targetAudience = $request->target_audience ?? 'General audience';
            $contentLanguage = $request->content_language ?? 'tr';

            $locale = app()->getLocale();

            if ($locale === 'tr') {
                $prompt = <<<PROMPT
Aşağıdaki içerik üretici profiline göre, düşük rekabetli ve yüksek büyüme potansiyelli en iyi YouTube nişlerini öner:

**İçerik Üretici Profili:**
- İlgi Alanları: {$interests}
- Yetenekler: {$skills}
- Yaşam Tarzı: {$lifestyle}
- Zaman Müsaitliği: {$timeAvailability}
- Hedef Kitle: {$targetAudience}
- İçerik Dili: {$contentLanguage}

JSON formatında detaylı bir analiz sun. TÜM METİNLER TÜRKÇE OLMALIDIR:
1. suggested_niches (dizi, her biri şu alanları içermeli: name, competition_level (düşük/orta/yüksek), growth_potential, why_suitable, content_format) - TÜM DEĞERLERİ TÜRKÇE YAZ
2. content_ideas (10 adet spesifik video fikri dizisi) - TÜRKÇE YAZ
3. monetization_potential (ad_revenue_potential, sponsorship_potential, product_potential, timeline_to_monetization içeren obje) - TÜRKÇE YAZ
4. competition_data (main_competitors, market_gap, differentiation_tips içeren obje) - TÜRKÇE YAZ
5. recommendations (uygulanabilir ipuçları dizisi) - TÜRKÇE YAZ
6. ai_summary (2-3 paragraf özet) - TÜRKÇE YAZ

ÖNEMLİ: Tüm alan değerleri (name, why_suitable, content_format, content_ideas, recommendations, ai_summary dahil HER ŞEY) TÜRKÇE olmalıdır. İngilizce kelime kullanma.
PROMPT;
                $systemPrompt = 'Sen bir YouTube niş uzmanısın. Yeni içerik üreticileri için gerçekçi, veri odaklı öneriler sun. MUTLAKA tüm yanıtlarını ve JSON içindeki tüm metin değerlerini Türkçe olarak ver. İngilizce kullanma.';
            } else {
                $prompt = <<<PROMPT
Based on the following creator profile, suggest the best YouTube niches with low competition and high growth potential:

**Creator Profile:**
- Interests: {$interests}
- Skills: {$skills}
- Lifestyle: {$lifestyle}
- Time Availability: {$timeAvailability}
- Target Audience: {$targetAudience}
- Content Language: {$contentLanguage}

Please provide a detailed analysis in JSON format with:
1. suggested_niches (array of objects with: name, competition_level, growth_potential, why_suitable, content_format)
2. content_ideas (array of 10 specific video ideas)
3. monetization_potential (object with: ad_revenue_potential, sponsorship_potential, product_potential, timeline_to_monetization)
4. competition_data (object with: main_competitors, market_gap, differentiation_tips)
5. recommendations (array of actionable tips)
6. ai_summary (2-3 paragraph summary)

Focus on niches that match the creator's profile and have realistic growth potential.
PROMPT;
                $systemPrompt = 'You are a YouTube niche expert. Provide realistic, data-driven recommendations for aspiring creators.';
            }

            $result = $this->ai->generateJson($prompt, [], [
                'system_prompt' => $systemPrompt,
            ]);

            // Create analysis record
            $analysis = NicheAnalysis::create([
                'user_id' => $user->id,
                'interests' => $request->interests,
                'skills' => $request->skills ?? [],
                'lifestyle' => $request->lifestyle ? [$request->lifestyle] : [],
                'time_availability' => $timeAvailability,
                'content_language' => $contentLanguage,
                'target_audience' => $targetAudience,
                'suggested_niches' => $result['suggested_niches'] ?? [],
                'content_ideas' => $result['content_ideas'] ?? [],
                'monetization_potential' => $result['monetization_potential'] ?? [],
                'competition_data' => $result['competition_data'] ?? [],
                'recommendations' => $result['recommendations'] ?? [],
                'ai_summary' => $result['ai_summary'] ?? '',
                'credits_used' => $this->creditManager->getCost($operation),
                'model_used' => $this->ai->getModelName(),
            ]);

            // Deduct credits
            $this->creditManager->deduct(
                $user,
                $operation,
                __('credits.used_for', ['feature' => __('modules.niche_analysis')]),
                $analysis
            );

            // Log activity
            ActivityLog::log('niche_analysis', $analysis);

            return response()->json([
                'success' => true,
                'data' => $analysis,
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
        $analysis = $request->user()->nicheAnalyses()->findOrFail($id);

        return view('dashboard.niche-analysis-result', compact('analysis'));
    }
}
