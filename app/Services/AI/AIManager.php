<?php

namespace App\Services\AI;

use App\Services\AI\Contracts\AIProviderInterface;
use Illuminate\Support\Manager;

class AIManager extends Manager
{
    /**
     * Get the default driver name.
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('services.ai.default_provider', 'openai');
    }

    /**
     * Create OpenAI driver.
     */
    public function createOpenaiDriver(): AIProviderInterface
    {
        return $this->container->make(OpenAIService::class);
    }

    /**
     * Create Claude (Anthropic) driver.
     */
    public function createClaudeDriver(): AIProviderInterface
    {
        return $this->container->make(ClaudeService::class);
    }

    /**
     * Create Anthropic driver (alias for Claude).
     */
    public function createAnthropicDriver(): AIProviderInterface
    {
        return $this->createClaudeDriver();
    }

    /**
     * Get specific provider.
     */
    public function provider(string $name): AIProviderInterface
    {
        return $this->driver($name);
    }

    /**
     * Analyze content with default provider.
     */
    public function analyze(string $prompt, array $context = []): string
    {
        return $this->driver()->analyze($prompt, $context);
    }

    /**
     * Generate text with default provider.
     */
    public function generateText(string $prompt, array $options = []): string
    {
        return $this->driver()->generateText($prompt, $options);
    }

    /**
     * Generate JSON with default provider.
     */
    public function generateJson(string $prompt, array $schema = [], array $options = []): array
    {
        return $this->driver()->generateJson($prompt, $schema, $options);
    }

    /**
     * Analyze image with default provider.
     */
    public function analyzeImage(string $imagePath, string $prompt): string
    {
        return $this->driver()->analyzeImage($imagePath, $prompt);
    }

    /**
     * Get current model name.
     */
    public function getModelName(): string
    {
        return $this->driver()->getModelName();
    }

    /**
     * Get last usage stats.
     */
    public function getLastUsage(): array
    {
        return $this->driver()->getLastUsage();
    }
}
