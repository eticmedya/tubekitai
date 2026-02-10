<?php

namespace App\Services\AI;

use App\Services\AI\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaudeService implements AIProviderInterface
{
    protected string $apiKey;
    protected string $model;
    protected int $maxTokens;
    protected float $temperature;
    protected array $lastUsage = [];

    public function __construct(
        ?string $apiKey = null,
        ?string $model = null,
        ?int $maxTokens = null,
        ?float $temperature = null
    ) {
        $this->apiKey = $apiKey ?? config('services.ai.anthropic.api_key');
        $this->model = $model ?? config('services.ai.anthropic.model', 'claude-3-5-sonnet-20241022');
        $this->maxTokens = $maxTokens ?? config('services.ai.anthropic.max_tokens', 4096);
        $this->temperature = $temperature ?? config('services.ai.anthropic.temperature', 0.7);
    }

    public function analyze(string $prompt, array $context = []): string
    {
        $systemPrompt = $context['system_prompt'] ?? 'You are a helpful YouTube content analysis assistant. Provide detailed, actionable insights.';

        return $this->message($prompt, $systemPrompt);
    }

    public function generateText(string $prompt, array $options = []): string
    {
        $systemPrompt = $options['system_prompt'] ?? 'You are a creative YouTube content assistant. Generate engaging, SEO-friendly content.';

        return $this->message($prompt, $systemPrompt, $options);
    }

    public function generateJson(string $prompt, array $schema = [], array $options = []): array
    {
        $systemPrompt = $options['system_prompt'] ?? 'You are a helpful assistant. Always respond with valid JSON only, no additional text.';

        if (!empty($schema)) {
            $systemPrompt .= "\n\nResponse must follow this JSON schema:\n" . json_encode($schema, JSON_PRETTY_PRINT);
        }

        $response = $this->message($prompt, $systemPrompt, $options);

        // Extract JSON from response (Claude might add explanation)
        $jsonMatch = preg_match('/\{[\s\S]*\}/', $response, $matches);
        if ($jsonMatch) {
            return json_decode($matches[0], true) ?? [];
        }

        return json_decode($response, true) ?? [];
    }

    public function analyzeImage(string $imagePath, string $prompt): string
    {
        $imageData = base64_encode(file_get_contents($imagePath));
        $mimeType = mime_content_type($imagePath);

        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'image',
                            'source' => [
                                'type' => 'base64',
                                'media_type' => $mimeType,
                                'data' => $imageData,
                            ],
                        ],
                        [
                            'type' => 'text',
                            'text' => $prompt,
                        ],
                    ],
                ],
            ],
        ]);

        if ($response->failed()) {
            Log::error('Claude Vision API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Failed to analyze image: ' . $response->body());
        }

        $data = $response->json();
        $this->lastUsage = [
            'input_tokens' => $data['usage']['input_tokens'] ?? 0,
            'output_tokens' => $data['usage']['output_tokens'] ?? 0,
        ];

        return $data['content'][0]['text'] ?? '';
    }

    public function estimateTokens(string $text): int
    {
        // Claude uses similar tokenization to GPT
        // Rough estimation: ~4 characters per token
        return (int) ceil(mb_strlen($text) / 3.5);
    }

    public function getModelName(): string
    {
        return $this->model;
    }

    public function getLastUsage(): array
    {
        return [
            'input_tokens' => $this->lastUsage['input_tokens'] ?? 0,
            'output_tokens' => $this->lastUsage['output_tokens'] ?? 0,
            'total_tokens' => ($this->lastUsage['input_tokens'] ?? 0) + ($this->lastUsage['output_tokens'] ?? 0),
        ];
    }

    protected function message(string $prompt, string $systemPrompt = '', array $options = []): string
    {
        $payload = [
            'model' => $options['model'] ?? $this->model,
            'max_tokens' => $options['max_tokens'] ?? $this->maxTokens,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ];

        if (!empty($systemPrompt)) {
            $payload['system'] = $systemPrompt;
        }

        if (isset($options['temperature'])) {
            $payload['temperature'] = $options['temperature'];
        } else {
            $payload['temperature'] = $this->temperature;
        }

        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.anthropic.com/v1/messages', $payload);

        if ($response->failed()) {
            Log::error('Claude API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Claude API request failed: ' . $response->body());
        }

        $data = $response->json();
        $this->lastUsage = [
            'input_tokens' => $data['usage']['input_tokens'] ?? 0,
            'output_tokens' => $data['usage']['output_tokens'] ?? 0,
        ];

        return $data['content'][0]['text'] ?? '';
    }
}
