<?php

namespace App\Services\Image;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FalAIService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $model;

    // Aspect ratio mappings
    protected array $aspectRatios = [
        '16:9' => '16:9',   // YouTube thumbnail (landscape)
        '9:16' => '9:16',   // YouTube Shorts (portrait)
        '1:1' => '1:1',     // Square
        '4:3' => '4:3',     // Classic
        '3:4' => '3:4',     // Portrait classic
    ];

    public function __construct()
    {
        $this->apiKey = config('services.fal.api_key');
        $this->baseUrl = config('services.fal.base_url', 'https://fal.run');
        $this->model = config('services.fal.model', 'fal-ai/nano-banana-pro');
    }

    /**
     * Generate a cover image.
     */
    public function generateCover(string $prompt, array $options = []): array
    {
        // Get aspect ratio (default: 16:9 for YouTube thumbnails)
        $aspectRatio = $options['aspect_ratio'] ?? '16:9';

        // Validate aspect ratio
        if (!isset($this->aspectRatios[$aspectRatio])) {
            $aspectRatio = '16:9';
        }

        // Enhance prompt with professional CTR-focused additions
        $enhancedPrompt = $this->enhancePrompt($prompt, $aspectRatio);

        $payload = [
            'prompt' => $enhancedPrompt,
            'num_images' => $options['num_images'] ?? 1,
            'aspect_ratio' => $this->aspectRatios[$aspectRatio],
            'output_format' => $options['output_format'] ?? 'png',
            'resolution' => $options['resolution'] ?? '1K',
        ];

        // Add seed if provided
        if (isset($options['seed'])) {
            $payload['seed'] = $options['seed'];
        }

        // Log the enhanced prompt for debugging
        Log::info('Fal.ai Cover Generation', [
            'user_prompt' => $prompt,
            'enhanced_prompt' => $enhancedPrompt,
            'aspect_ratio' => $aspectRatio,
        ]);

        // Build HTTP client with SSL bypass for local development
        $http = Http::withHeaders([
            'Authorization' => 'Key ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(180);

        // Disable SSL verification for local development
        if (app()->environment('local')) {
            $http = $http->withOptions(['verify' => false]);
        }

        $response = $http->post("{$this->baseUrl}/{$this->model}", $payload);

        if ($response->failed()) {
            Log::error('Fal.ai API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Cover generation failed: ' . $response->body());
        }

        $data = $response->json();

        if (empty($data['images'])) {
            throw new \Exception('No images generated');
        }

        // Download and store image
        $imageUrl = $data['images'][0]['url'];
        $storedPath = $this->downloadAndStore($imageUrl);

        return [
            'path' => $storedPath,
            'url' => Storage::disk('covers')->url($storedPath),
            'original_url' => $imageUrl,
            'prompt' => $prompt,
            'aspect_ratio' => $aspectRatio,
            'seed' => $data['seed'] ?? null,
        ];
    }

    /**
     * Generate cover variations.
     */
    public function generateVariations(string $prompt, int $count = 4, string $aspectRatio = '16:9'): array
    {
        $variations = [];

        for ($i = 0; $i < $count; $i++) {
            try {
                $variation = $this->generateCover($prompt, [
                    'seed' => random_int(1, 999999999),
                    'aspect_ratio' => $aspectRatio,
                ]);
                $variations[] = $variation;
            } catch (\Exception $e) {
                Log::warning('Failed to generate variation', [
                    'index' => $i,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $variations;
    }

    /**
     * Generate cover from reference image (image-to-image editing).
     */
    public function generateFromReference(string $prompt, string $referenceImagePath, array $options = []): array
    {
        $imageData = base64_encode(file_get_contents($referenceImagePath));
        $mimeType = mime_content_type($referenceImagePath);
        $dataUri = "data:{$mimeType};base64,{$imageData}";

        $aspectRatio = $options['aspect_ratio'] ?? '16:9';

        // Use reference-specific prompt enhancement
        $enhancedPrompt = $this->enhancePromptWithReference($prompt, $aspectRatio);

        $endpoint = "{$this->baseUrl}/{$this->model}/edit";

        // Log the request details
        Log::info('Fal.ai Image Editing Request', [
            'endpoint' => $endpoint,
            'user_prompt' => $prompt,
            'enhanced_prompt' => $enhancedPrompt,
            'aspect_ratio' => $aspectRatio,
            'has_reference_image' => true,
        ]);

        // Build HTTP client with SSL bypass for local development
        $http = Http::withHeaders([
            'Authorization' => 'Key ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(180);

        // Disable SSL verification for local development
        if (app()->environment('local')) {
            $http = $http->withOptions(['verify' => false]);
        }

        $response = $http->post($endpoint, [
            'prompt' => $enhancedPrompt,
            'image_urls' => [$dataUri],
            'aspect_ratio' => $this->aspectRatios[$aspectRatio] ?? '16:9',
            'output_format' => 'png',
            'resolution' => '1K',
        ]);

        if ($response->failed()) {
            Log::error('Fal.ai Image Edit Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Cover generation from reference failed: ' . $response->body());
        }

        $data = $response->json();

        if (empty($data['images'])) {
            throw new \Exception('No images generated');
        }

        $imageUrl = $data['images'][0]['url'];
        $storedPath = $this->downloadAndStore($imageUrl);

        return [
            'path' => $storedPath,
            'url' => Storage::disk('covers')->url($storedPath),
            'original_url' => $imageUrl,
            'prompt' => $prompt,
            'aspect_ratio' => $aspectRatio,
        ];
    }

    /**
     * Enhance prompt for better image generation (without reference image).
     * Adds professional CTR-focused YouTube thumbnail prompt enhancements.
     */
    protected function enhancePrompt(string $userPrompt, string $aspectRatio = '16:9'): string
    {
        // Professional YouTube thumbnail style prompt
        $stylePrompt = "Hyper-realistic cinematic YouTube thumbnail. Shot on Sony A7R IV, 35mm f/1.4 lens. 8K resolution, photorealistic textures, ray-traced lighting. Professional studio setup with dramatic rim lighting. Teal and orange color grade. Bold 3D rendered sans-serif typography with chrome metallic effect, slight glow, and depth shadow. Text should pop with high contrast against the background.";

        // Aspect ratio specific additions
        if ($aspectRatio === '9:16') {
            $formatPrompt = "Vertical YouTube Shorts composition. Mobile-optimized with centered subject. Strong visual impact.";
        } else {
            $formatPrompt = "Landscape 16:9 YouTube thumbnail composition. Strong visual hierarchy, click-worthy appeal.";
        }

        // User prompt FIRST (their specific content), then style enhancements
        $finalPrompt = "{$userPrompt}. {$stylePrompt} {$formatPrompt}";

        return $finalPrompt;
    }

    /**
     * Enhance prompt for image-to-image generation (with reference image).
     * Uses lighting and color grading focused enhancements.
     * NOTE: Does NOT add text/typography instructions - only visual style enhancements.
     * Preserves person/product from reference image exactly.
     */
    protected function enhancePromptWithReference(string $userPrompt, string $aspectRatio = '16:9'): string
    {
        // CRITICAL: Subject preservation instructions - person identity or product must be kept 100% identical
        $subjectPreservation = "CRITICAL INSTRUCTION: If there is a person/human in the reference image, you MUST preserve their face, facial features, skin tone, hair style, hair color, and body proportions EXACTLY 100% identical - the person must be clearly recognizable as the same individual. However, you CAN change their pose, expression, and clothing to be more dynamic and professional for a YouTube thumbnail. Create an engaging, click-worthy pose suitable for the content. Replace the background with a professional, eye-catching scene. If there is a product/object in the reference image, you MUST preserve the product EXACTLY 100% identical - same exact shape, color, texture, branding, logos, and all details must remain unchanged. Only enhance the background and lighting around the product.";

        // Professional visual style prompt - NO text instructions to avoid duplicate text
        $stylePrompt = "Professional YouTube thumbnail style with cinematic lighting. Sharp rim light separating subject from background. Soft teal and orange color grade (Hollywood blockbuster look). 8K resolution, hyper-realistic textures. Professional studio setup with dramatic shadows.";

        // Aspect ratio specific additions
        if ($aspectRatio === '9:16') {
            $formatPrompt = "Vertical composition for YouTube Shorts. Mobile-optimized, centered subject with dramatic visual impact.";
        } else {
            $formatPrompt = "Landscape 16:9 YouTube thumbnail composition. Strong visual hierarchy with eye-catching appeal.";
        }

        // Subject preservation FIRST (most important), then user prompt, then style enhancements
        $finalPrompt = "{$subjectPreservation} {$userPrompt}. {$stylePrompt} {$formatPrompt}";

        return $finalPrompt;
    }

    /**
     * Download and store generated image.
     */
    protected function downloadAndStore(string $url): string
    {
        $http = Http::timeout(30);

        // Disable SSL verification for local development
        if (app()->environment('local')) {
            $http = $http->withOptions(['verify' => false]);
        }

        $response = $http->get($url);

        if ($response->failed()) {
            throw new \Exception('Failed to download generated image');
        }

        $filename = 'cover_' . time() . '_' . bin2hex(random_bytes(8)) . '.png';
        $path = date('Y/m/') . $filename;

        Storage::disk('covers')->put($path, $response->body());

        return $path;
    }

    /**
     * Get suggested prompts for a topic.
     */
    public function getSuggestedPrompts(string $topic, string $style = 'modern'): array
    {
        $templates = [
            'modern' => [
                "{$topic}, modern minimalist design, bold typography space, gradient background, 3D elements",
                "{$topic}, neon glow effect, dark background, futuristic style, tech aesthetic",
                "{$topic}, clean professional look, subtle shadows, corporate style, elegant",
            ],
            'gaming' => [
                "{$topic}, epic gaming scene, dramatic lighting, action packed, vibrant colors",
                "{$topic}, game art style, fantasy elements, magical effects, detailed illustration",
                "{$topic}, esports style, competitive gaming aesthetic, dynamic composition",
            ],
            'vlog' => [
                "{$topic}, lifestyle photography style, warm tones, authentic feel, personal",
                "{$topic}, travel vlog aesthetic, adventure vibes, scenic background, wanderlust",
                "{$topic}, daily vlog style, relatable, casual, friendly atmosphere",
            ],
            'educational' => [
                "{$topic}, infographic style, clear visual hierarchy, educational design, informative",
                "{$topic}, documentary style, professional, trustworthy, knowledge sharing",
                "{$topic}, classroom aesthetic, learning environment, study materials visual",
            ],
        ];

        return $templates[$style] ?? $templates['modern'];
    }
}
