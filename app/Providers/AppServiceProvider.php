<?php

namespace App\Providers;

use App\Services\AI\AIManager;
use App\Services\AI\ClaudeService;
use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\OpenAIService;
use App\Services\Credit\CreditManager;
use App\Services\Payment\PayTRService;
use App\Services\YouTube\YouTubeAPIService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register AI Manager as singleton
        $this->app->singleton(AIManager::class, function ($app) {
            return new AIManager($app);
        });

        // Register default AI provider based on config
        $this->app->bind(AIProviderInterface::class, function ($app) {
            return $app->make(AIManager::class)->driver();
        });

        // Register OpenAI Service
        $this->app->singleton(OpenAIService::class, function ($app) {
            return new OpenAIService(
                config('services.ai.openai.api_key'),
                config('services.ai.openai.model'),
                config('services.ai.openai.max_tokens'),
                config('services.ai.openai.temperature')
            );
        });

        // Register Claude Service
        $this->app->singleton(ClaudeService::class, function ($app) {
            return new ClaudeService(
                config('services.ai.anthropic.api_key'),
                config('services.ai.anthropic.model'),
                config('services.ai.anthropic.max_tokens'),
                config('services.ai.anthropic.temperature')
            );
        });

        // Register YouTube API Service
        $this->app->singleton(YouTubeAPIService::class, function ($app) {
            return new YouTubeAPIService(
                config('services.youtube.api_key')
            );
        });

        // Register Credit Manager
        $this->app->singleton(CreditManager::class, function ($app) {
            return new CreditManager();
        });

        // Register PayTR Service
        $this->app->singleton(PayTRService::class, function ($app) {
            return new PayTRService(
                config('services.paytr.merchant_id'),
                config('services.paytr.merchant_key'),
                config('services.paytr.merchant_salt'),
                config('services.paytr.test_mode')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom Blade directives
        Blade::directive('credits', function ($expression) {
            return "<?php echo number_format(auth()->user()->credits ?? 0, 1); ?>";
        });

        Blade::directive('money', function ($expression) {
            return "<?php echo number_format({$expression} / 100, 2, ',', '.') . ' ₺'; ?>";
        });

        // Register Blade components namespace
        Blade::componentNamespace('App\\View\\Components', 'tubekit');
    }
}
