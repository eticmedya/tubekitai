<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Image\FalAIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateCoverJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 180;

    public function __construct(
        public string $prompt,
        public User $user,
        public ?string $referenceImagePath = null
    ) {}

    public function handle(FalAIService $falAI): void
    {
        try {
            if ($this->referenceImagePath) {
                $result = $falAI->generateFromReference($this->prompt, $this->referenceImagePath);
            } else {
                $result = $falAI->generateCover($this->prompt);
            }

            Log::info('Cover generation completed', [
                'user_id' => $this->user->id,
                'prompt' => $this->prompt,
                'path' => $result['path'],
            ]);
        } catch (\Exception $e) {
            Log::error('Cover generation failed', [
                'user_id' => $this->user->id,
                'prompt' => $this->prompt,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Cover generation job failed permanently', [
            'user_id' => $this->user->id,
            'prompt' => $this->prompt,
            'error' => $exception->getMessage(),
        ]);
    }
}
