<?php

namespace App\Jobs;

use App\Models\Translation;
use App\Models\User;
use App\Services\AI\AIManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranslateContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(
        public User $user,
        public string $type,
        public string $sourceText,
        public string $sourceLang,
        public string $targetLang,
        public ?int $videoId = null
    ) {}

    public function handle(AIManager $ai): void
    {
        try {
            $prompt = $this->buildPrompt();

            $result = $ai->generateJson($prompt, [], [
                'system_prompt' => 'You are an expert YouTube content translator. Translate content while maintaining SEO effectiveness and natural flow in the target language.',
            ]);

            $translation = Translation::create([
                'user_id' => $this->user->id,
                'video_id' => $this->videoId,
                'type' => $this->type,
                'source_text' => $this->sourceText,
                'source_lang' => $this->sourceLang,
                'target_lang' => $this->targetLang,
                'translated_text' => $result['translation'] ?? $result['translated_text'] ?? '',
                'seo_suggestions' => $result['seo_suggestions'] ?? [],
                'model_used' => $ai->getModelName(),
            ]);

            Log::info('Translation completed', [
                'translation_id' => $translation->id,
                'user_id' => $this->user->id,
                'type' => $this->type,
                'language_pair' => "{$this->sourceLang} -> {$this->targetLang}",
            ]);
        } catch (\Exception $e) {
            Log::error('Translation failed', [
                'user_id' => $this->user->id,
                'type' => $this->type,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    protected function buildPrompt(): string
    {
        $typeLabel = match ($this->type) {
            'title' => 'YouTube video title',
            'description' => 'YouTube video description',
            'subtitle' => 'YouTube video subtitles',
            'tags' => 'YouTube video tags',
            default => 'content',
        };

        return <<<PROMPT
Translate the following {$typeLabel} from {$this->sourceLang} to {$this->targetLang}.

Original text:
{$this->sourceText}

Requirements:
1. Maintain SEO effectiveness in the target language
2. Keep the tone and style consistent
3. Adapt cultural references if needed
4. For titles: Keep them clickable and engaging
5. For descriptions: Maintain keyword density
6. For tags: Translate and add relevant local keywords

Return JSON with:
- translation: The translated text
- seo_suggestions: Array of SEO tips for the target language
PROMPT;
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Translation job failed permanently', [
            'user_id' => $this->user->id,
            'type' => $this->type,
            'error' => $exception->getMessage(),
        ]);
    }
}
