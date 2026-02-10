<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use App\Services\AI\AIManager;
use App\Services\Credit\CreditManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransFlowController extends Controller
{
    public function __construct(
        protected AIManager $ai,
        protected CreditManager $credits
    ) {}

    public function index(Request $request): View
    {
        $translations = Translation::where('user_id', $request->user()->id)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.transflow', compact('translations'));
    }

    public function translate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:title,description,tags,subtitle',
            'source_text' => 'required|string|max:10000',
            'source_lang' => 'required|string|max:10',
            'target_lang' => 'required|string|max:10',
        ]);

        $user = $request->user();

        // Check credits
        if (!$this->credits->hasEnough($user, 'transflow_subtitle')) {
            return response()->json([
                'success' => false,
                'message' => __('credits.insufficient_credits'),
            ], 402);
        }

        try {
            $prompt = $this->buildTranslationPrompt($validated);

            $result = $this->ai->generateJson($prompt, [], [
                'system_prompt' => 'You are an expert YouTube content translator. Translate content while maintaining SEO effectiveness and natural flow in the target language. Always return valid JSON.',
            ]);

            // Save translation
            $translation = Translation::create([
                'user_id' => $user->id,
                'type' => $validated['type'],
                'source_text' => $validated['source_text'],
                'source_lang' => $validated['source_lang'],
                'target_lang' => $validated['target_lang'],
                'translated_text' => $result['translation'] ?? $result['translated_text'] ?? '',
                'seo_suggestions' => $result['seo_suggestions'] ?? [],
                'model_used' => $this->ai->getModelName(),
            ]);

            // Deduct credits
            $this->credits->deduct($user, 'transflow_subtitle', "Translation: {$validated['type']}");

            return response()->json([
                'success' => true,
                'data' => [
                    'translation' => $translation->translated_text,
                    'seo_suggestions' => $translation->seo_suggestions,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('app.error_occurred'),
            ], 500);
        }
    }

    protected function buildTranslationPrompt(array $data): string
    {
        $typeLabel = match ($data['type']) {
            'title' => 'YouTube video title',
            'description' => 'YouTube video description',
            'subtitle' => 'YouTube video subtitles',
            'tags' => 'YouTube video tags',
            default => 'content',
        };

        $sourceLang = $data['source_lang'] === 'auto' ? 'the detected language' : $data['source_lang'];

        return <<<PROMPT
Translate the following {$typeLabel} from {$sourceLang} to {$data['target_lang']}.

Original text:
{$data['source_text']}

Requirements:
1. Maintain SEO effectiveness in the target language
2. Keep the tone and style consistent
3. Adapt cultural references if needed
4. For titles: Keep them clickable and engaging
5. For descriptions: Maintain keyword density
6. For tags: Translate and add relevant local keywords

Return JSON with:
- translation: The translated text
- seo_suggestions: Array of SEO tips for the target language (max 5 tips)
PROMPT;
    }
}
