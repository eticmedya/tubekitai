<?php

namespace App\Services\AI\Contracts;

interface AIProviderInterface
{
    /**
     * Analyze content with AI.
     */
    public function analyze(string $prompt, array $context = []): string;

    /**
     * Generate text with AI.
     */
    public function generateText(string $prompt, array $options = []): string;

    /**
     * Generate structured JSON response.
     */
    public function generateJson(string $prompt, array $schema = [], array $options = []): array;

    /**
     * Analyze image with AI (multimodal).
     */
    public function analyzeImage(string $imagePath, string $prompt): string;

    /**
     * Estimate token count for text.
     */
    public function estimateTokens(string $text): int;

    /**
     * Get the model name.
     */
    public function getModelName(): string;

    /**
     * Get last usage stats.
     */
    public function getLastUsage(): array;
}
