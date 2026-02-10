<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\YouTube\CommentAnalyzer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzeCommentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 600;

    public function __construct(
        public string $videoUrl,
        public User $user,
        public int $limit = 100
    ) {}

    public function handle(CommentAnalyzer $analyzer): void
    {
        try {
            $result = $analyzer->analyze($this->videoUrl, $this->user, $this->limit);

            Log::info('Comment analysis completed', [
                'video_id' => $result['video']->id,
                'analysis_id' => $result['analysis']->id,
                'user_id' => $this->user->id,
                'comment_count' => $result['summary']['total'],
            ]);
        } catch (\Exception $e) {
            Log::error('Comment analysis failed', [
                'video_url' => $this->videoUrl,
                'user_id' => $this->user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Comment analysis job failed permanently', [
            'video_url' => $this->videoUrl,
            'user_id' => $this->user->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
