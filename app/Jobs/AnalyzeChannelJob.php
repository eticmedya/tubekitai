<?php

namespace App\Jobs;

use App\Models\Channel;
use App\Models\User;
use App\Services\YouTube\ChannelAnalyzer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzeChannelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(
        public string $channelUrl,
        public User $user
    ) {}

    public function handle(ChannelAnalyzer $analyzer): void
    {
        try {
            $result = $analyzer->analyze($this->channelUrl, $this->user);

            Log::info('Channel analysis completed', [
                'channel_id' => $result['channel']->id,
                'user_id' => $this->user->id,
            ]);

            // You could dispatch an event or notification here
            // event(new ChannelAnalysisCompleted($result['channel'], $this->user));
        } catch (\Exception $e) {
            Log::error('Channel analysis failed', [
                'channel_url' => $this->channelUrl,
                'user_id' => $this->user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Channel analysis job failed permanently', [
            'channel_url' => $this->channelUrl,
            'user_id' => $this->user->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
