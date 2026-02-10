<?php

namespace App\Services\AI;

use App\Services\AI\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService implements AIProviderInterface
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
        $this->apiKey = $apiKey ?? config('services.ai.openai.api_key');
        $this->model = $model ?? config('services.ai.openai.model', 'gpt-4-turbo-preview');
        $this->maxTokens = $maxTokens ?? config('services.ai.openai.max_tokens', 4096);
        $this->temperature = $temperature ?? config('services.ai.openai.temperature', 0.7);
    }

    public function analyze(string $prompt, array $context = []): string
    {
        $systemPrompt = $context['system_prompt'] ?? 'You are a helpful YouTube content analysis assistant. Provide detailed, actionable insights.';

        return $this->chat([
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $prompt],
        ]);
    }

    public function generateText(string $prompt, array $options = []): string
    {
        $systemPrompt = $options['system_prompt'] ?? 'You are a creative YouTube content assistant. Generate engaging, SEO-friendly content.';

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $prompt],
        ];

        return $this->chat($messages, $options);
    }

    public function generateJson(string $prompt, array $schema = [], array $options = []): array
    {
        $systemPrompt = $options['system_prompt'] ?? 'You are a helpful assistant. Always respond with valid JSON.';

        if (!empty($schema)) {
            $systemPrompt .= "\n\nResponse must follow this JSON schema:\n" . json_encode($schema, JSON_PRETTY_PRINT);
        }

        $response = $this->chat([
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $prompt],
        ], ['response_format' => ['type' => 'json_object']]);

        return json_decode($response, true) ?? [];
    }

    public function analyzeImage(string $imagePath, string $prompt): string
    {
        $imageData = base64_encode(file_get_contents($imagePath));
        $mimeType = mime_content_type($imagePath);

        // Local development için SSL doğrulamasını devre dışı bırak
        $http = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(60);

        if (app()->environment('local')) {
            $http = $http->withOptions(['verify' => false]);
        }

        $response = $http->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $prompt,
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => "data:{$mimeType};base64,{$imageData}",
                            ],
                        ],
                    ],
                ],
            ],
            'max_tokens' => $this->maxTokens,
        ]);

        if ($response->failed()) {
            Log::error('OpenAI Vision API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Failed to analyze image: ' . $response->body());
        }

        $data = $response->json();
        $this->lastUsage = $data['usage'] ?? [];

        return $data['choices'][0]['message']['content'] ?? '';
    }

    public function estimateTokens(string $text): int
    {
        // Rough estimation: ~4 characters per token for English
        // For multilingual content, use ~3 characters per token
        return (int) ceil(mb_strlen($text) / 3.5);
    }

    public function getModelName(): string
    {
        return $this->model;
    }

    public function getLastUsage(): array
    {
        return [
            'input_tokens' => $this->lastUsage['prompt_tokens'] ?? 0,
            'output_tokens' => $this->lastUsage['completion_tokens'] ?? 0,
            'total_tokens' => $this->lastUsage['total_tokens'] ?? 0,
        ];
    }

    protected function chat(array $messages, array $options = []): string
    {
        $payload = [
            'model' => $options['model'] ?? $this->model,
            'messages' => $messages,
            'max_tokens' => $options['max_tokens'] ?? $this->maxTokens,
            'temperature' => $options['temperature'] ?? $this->temperature,
        ];

        if (isset($options['response_format'])) {
            $payload['response_format'] = $options['response_format'];
        }

        // Local development için SSL doğrulamasını devre dışı bırak
        $http = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120);

        if (app()->environment('local')) {
            $http = $http->withOptions(['verify' => false]);
        }

        $response = $http->post('https://api.openai.com/v1/chat/completions', $payload);

        if ($response->failed()) {
            Log::error('OpenAI API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('OpenAI API request failed: ' . $response->body());
        }

        $data = $response->json();
        $this->lastUsage = $data['usage'] ?? [];

        return $data['choices'][0]['message']['content'] ?? '';
    }
}
