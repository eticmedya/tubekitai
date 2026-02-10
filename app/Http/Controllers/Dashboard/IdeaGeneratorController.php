<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AIGeneration;
use App\Services\AI\AIManager;
use App\Services\Credit\CreditManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IdeaGeneratorController extends Controller
{
    public function __construct(
        protected AIManager $ai,
        protected CreditManager $creditManager
    ) {}

    /**
     * Display the idea generator page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get previous generations
        $previousGenerations = $user->aiGenerations()
            ->where('type', 'idea')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('dashboard.idea-generator', compact('previousGenerations'));
    }

    /**
     * Generate video ideas.
     */
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'mode' => ['required', 'string', 'in:topic,channel,trending'],
            'topic' => ['nullable', 'string', 'max:200'],
            'channel_url' => ['nullable', 'string', 'max:500'],
            'category' => ['nullable', 'string', 'max:100'],
            'count' => ['nullable', 'integer', 'min:3', 'max:20'],
        ]);

        $user = $request->user();
        $operation = 'video_idea_generation';

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
            $mode = $request->mode;
            $topic = $request->topic ?? '';
            $category = $request->category ?? '';
            $count = $request->count ?? 10;

            // Determine niche based on mode
            $niche = match($mode) {
                'topic' => $topic,
                'channel' => 'based on channel analysis',
                'trending' => $category ?: 'general trending topics',
            };

            $locale = app()->getLocale();
            $currentYear = date('Y');
            $currentMonth = date('F');

            if ($locale === 'tr') {
                $currentMonthTr = match((int)date('m')) {
                    1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                    5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                    9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık',
                    default => 'Ocak'
                };

                $prompt = <<<PROMPT
GÜNCEL TARİH: {$currentMonthTr} {$currentYear}

Aşağıdaki konu için {$count} adet benzersiz, yaratıcı ve GÜNCEL YouTube video fikri üret:

**Konu/Niş:** {$niche}
**Mod:** {$mode}

Her fikir için JSON formatında şunları sun:
{
  "ideas": [
    {
      "title": "SEO uyumlu, tıklanabilir Türkçe başlık ({$currentYear} yılına uygun)",
      "description": "Video konseptinin detaylı açıklaması (4-5 cümle). Videonun ne hakkında olacağını, hangi soruları cevaplayacağını ve izleyiciye ne katacağını açıkla.",
      "tags": ["etiket1", "etiket2", "etiket3", "etiket4", "etiket5"],
      "viral_potential": 75,
      "ctr_prediction": 8.5,
      "target_audience": "Hedef kitle açıklaması",
      "video_length": "Önerilen video süresi (örn: 10-15 dakika)",
      "content_outline": ["Ana başlık 1", "Ana başlık 2", "Ana başlık 3"]
    }
  ]
}

ÖNEMLİ KURALLAR:
1. Şu an {$currentYear} yılındayız. ESKİ TARİHLİ içerik önerme (2021, 2022, 2023, 2024, 2025 YASAK)
2. Başlıklarda yıl kullanacaksan SADECE {$currentYear} kullan
3. {$currentYear} yılının güncel trendlerini, teknolojilerini ve olaylarını baz al
4. Türkiye'de şu an popüler olan konulara odaklan
5. Her fikir detaylı ve uygulanabilir olmalı
6. Tüm içerik TÜRKÇE olmalı

Odaklan:
- {$currentYear} yılında Türkiye'de trend olan konular
- Güncel teknolojiler (AI, yapay zeka asistanları, yeni cihazlar vb.)
- Düşük rekabet, yüksek arama hacmi olan konular
- Özgün ve henüz çok işlenmemiş açılar
- Duygusal tetikleyiciler ve rakamlar içeren başlıklar
PROMPT;
                $systemPrompt = "Sen viral video konseptleri ve SEO uyumlu başlıklar konusunda uzman bir YouTube içerik stratejistisin. Şu an {$currentMonthTr} {$currentYear} yılındayız. SADECE güncel ve {$currentYear} yılına uygun içerik önerileri ver. Tüm yanıtlarını Türkçe olarak ver.";
            } else {
                $prompt = <<<PROMPT
CURRENT DATE: {$currentMonth} {$currentYear}

Generate {$count} unique, creative, and CURRENT YouTube video ideas for the following:

**Niche/Topic:** {$niche}
**Mode:** {$mode}

For each idea, provide in JSON format:
{
  "ideas": [
    {
      "title": "SEO-optimized clickable title (appropriate for {$currentYear})",
      "description": "Detailed description of the video concept (4-5 sentences). Explain what the video is about, what questions it answers, and what value it provides.",
      "tags": ["tag1", "tag2", "tag3", "tag4", "tag5"],
      "viral_potential": 75,
      "ctr_prediction": 8.5,
      "target_audience": "Target audience description",
      "video_length": "Recommended video length (e.g., 10-15 minutes)",
      "content_outline": ["Main point 1", "Main point 2", "Main point 3"]
    }
  ]
}

IMPORTANT RULES:
1. We are currently in {$currentYear}. DO NOT suggest outdated content (2021, 2022, 2023, 2024, 2025 are FORBIDDEN)
2. If using a year in titles, ONLY use {$currentYear}
3. Base ideas on {$currentYear} current trends, technologies, and events
4. Each idea must be detailed and actionable

Focus on:
- Topics trending in {$currentYear}
- Current technologies (AI assistants, new devices, etc.)
- Low competition, high search volume topics
- Unique angles that haven't been overdone
- Titles with emotional triggers and numbers
PROMPT;
                $systemPrompt = "You are a YouTube content strategist specializing in viral video concepts and SEO-optimized titles. We are currently in {$currentMonth} {$currentYear}. ONLY suggest current and {$currentYear}-appropriate content ideas.";
            }

            $result = $this->ai->generateJson($prompt, [], [
                'system_prompt' => $systemPrompt,
            ]);

            $usage = $this->ai->getLastUsage();

            // Create generation record
            $generation = AIGeneration::create([
                'user_id' => $user->id,
                'type' => 'idea',
                'prompt' => "Mode: {$mode}, Topic: {$niche}",
                'context' => [
                    'mode' => $mode,
                    'topic' => $topic,
                    'category' => $category,
                    'count' => $count,
                ],
                'result' => json_encode($result),
                'result_meta' => $result,
                'model_used' => $this->ai->getModelName(),
                'input_tokens' => $usage['input_tokens'] ?? 0,
                'output_tokens' => $usage['output_tokens'] ?? 0,
                'credits_used' => $this->creditManager->getCost($operation),
            ]);

            // Deduct credits
            $this->creditManager->deduct(
                $user,
                $operation,
                __('credits.used_for', ['feature' => __('modules.video_ideas')]),
                $generation
            );

            // Log activity
            ActivityLog::log('ai_generation', $generation);

            return response()->json([
                'success' => true,
                'data' => [
                    'generation' => $generation,
                    'ideas' => $result['ideas'] ?? [],
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
     * Toggle favorite status.
     */
    public function toggleFavorite(Request $request, int $id): JsonResponse
    {
        $generation = $request->user()->aiGenerations()->findOrFail($id);
        $isFavorite = $generation->toggleFavorite();

        return response()->json([
            'success' => true,
            'is_favorite' => $isFavorite,
        ]);
    }
}
