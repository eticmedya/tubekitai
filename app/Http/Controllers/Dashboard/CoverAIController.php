<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CoverAnalysis;
use App\Models\CoverGeneration;
use App\Services\AI\AIManager;
use App\Services\Credit\CreditManager;
use App\Services\Image\FalAIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CoverAIController extends Controller
{
    public function __construct(
        protected AIManager $ai,
        protected FalAIService $falAI,
        protected CreditManager $creditManager
    ) {}

    /**
     * Display the cover AI page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get previous analyses
        $previousAnalyses = $user->coverAnalyses()
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        // Get previous generations
        $previousGenerations = $user->coverGenerations()
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('dashboard.cover-ai', compact('previousAnalyses', 'previousGenerations'));
    }

    /**
     * Analyze a cover image.
     */
    public function analyze(Request $request): JsonResponse
    {
        // Increase PHP execution time for AI analysis
        set_time_limit(180);

        $request->validate([
            'image' => ['required_without:youtube_url', 'nullable', 'image', 'max:10240'], // 10MB max
            'youtube_url' => ['required_without:image', 'nullable', 'string', 'max:500'],
        ]);

        $user = $request->user();
        $operation = 'cover_analysis_score';

        // Check credits
        if (!$this->creditManager->hasEnough($user, $operation)) {
            return response()->json([
                'success' => false,
                'error' => __('credits.insufficient'),
                'required' => $this->creditManager->getCost($operation),
                'available' => $user->credits,
            ], 402);
        }

        try {
            $originalFilename = 'uploaded_image.jpg';
            $videoTitle = null;

            // Handle YouTube URL or uploaded image
            if ($request->youtube_url) {
                // Extract video ID from URL
                $videoId = $this->extractYouTubeVideoId($request->youtube_url);
                if (!$videoId) {
                    return response()->json([
                        'success' => false,
                        'error' => __('youtube.invalid_url'),
                    ], 422);
                }

                // Try to fetch thumbnail using cURL
                $thumbnailContent = null;
                $thumbnailUrls = [
                    "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg",
                    "https://img.youtube.com/vi/{$videoId}/sddefault.jpg",
                    "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg",
                ];

                foreach ($thumbnailUrls as $thumbnailUrl) {
                    $thumbnailContent = $this->fetchImageWithCurl($thumbnailUrl);
                    if ($thumbnailContent !== null) {
                        break;
                    }
                }

                if ($thumbnailContent === null) {
                    return response()->json([
                        'success' => false,
                        'error' => __('cover.thumbnail_fetch_failed'),
                    ], 422);
                }

                // Save thumbnail to storage
                $path = date('Y/m') . '/' . $videoId . '_' . time() . '.jpg';
                Storage::disk('covers')->put($path, $thumbnailContent);
                $fullPath = Storage::disk('covers')->path($path);
                $originalFilename = "youtube_{$videoId}.jpg";
            } else {
                // Store uploaded image
                $file = $request->file('image');
                $path = $file->store(date('Y/m'), 'covers');
                $fullPath = Storage::disk('covers')->path($path);
                $originalFilename = $file->getClientOriginalName();
            }

            // Get current locale for language-specific response
            $locale = app()->getLocale();
            $languageInstruction = $locale === 'tr'
                ? 'IMPORTANT: Respond ONLY in Turkish. All text including ai_feedback must be in Turkish.'
                : 'Respond in English.';

            // Analyze with AI
            $analysisPrompt = <<<PROMPT
Analyze this YouTube thumbnail/cover image and provide scores (0-100) for each category.

{$languageInstruction}

Scoring criteria:
1. overall_score (0-100) - Overall thumbnail quality and effectiveness
2. readability_score (0-100) - Text visibility, font choices, text contrast
3. contrast_score (0-100) - Color contrast, visual appeal, color harmony
4. face_visibility_score (0-100) - If faces present, how clear and expressive. If no face, score based on main subject visibility
5. emotion_score (0-100) - Emotional impact, curiosity-inducing elements, excitement level
6. ctr_prediction (0-100) - Estimated click-through rate potential as percentage

Also provide:
- ai_feedback: 2-3 sentences of constructive feedback about the thumbnail

CRITICAL: Return ONLY valid JSON with these exact keys and NUMBER values (not strings):
{
  "overall_score": 75,
  "readability_score": 80,
  "contrast_score": 70,
  "face_visibility_score": 85,
  "emotion_score": 72,
  "ctr_prediction": 78,
  "ai_feedback": "Your feedback here in the requested language"
}

Do not include any text before or after the JSON. Only return the JSON object.
PROMPT;

            $result = $this->ai->analyzeImage($fullPath, $analysisPrompt);

            // Parse JSON from response
            $scores = json_decode($result, true);

            if (!$scores) {
                // Try to extract JSON from text
                preg_match('/\{[\s\S]*\}/', $result, $matches);
                $scores = json_decode($matches[0] ?? '{}', true) ?? [];
            }

            // Ensure all scores are integers
            $overallScore = (int) ($scores['overall_score'] ?? 50);
            $readabilityScore = (int) ($scores['readability_score'] ?? 50);
            $contrastScore = (int) ($scores['contrast_score'] ?? 50);
            $faceVisibilityScore = (int) ($scores['face_visibility_score'] ?? 50);
            $emotionScore = (int) ($scores['emotion_score'] ?? 50);
            $ctrPrediction = (int) ($scores['ctr_prediction'] ?? 50);
            $aiFeedback = $scores['ai_feedback'] ?? $result;

            // Create analysis record
            $analysis = CoverAnalysis::create([
                'user_id' => $user->id,
                'image_path' => $path,
                'original_filename' => $originalFilename,
                'quality_score' => $overallScore,
                'readability_score' => $readabilityScore,
                'face_visibility_score' => $faceVisibilityScore,
                'contrast_score' => $contrastScore,
                'emotion_score' => $emotionScore,
                'composition_score' => $overallScore,
                'overall_score' => $overallScore,
                'ctr_prediction' => $ctrPrediction,
                'ai_feedback' => $aiFeedback,
                'improvement_suggestions' => $scores['improvement_suggestions'] ?? [],
                'detected_elements' => $scores['detected_elements'] ?? [],
                'credits_used' => $this->creditManager->getCost($operation),
                'model_used' => $this->ai->getModelName(),
            ]);

            // Deduct credits
            $this->creditManager->deduct(
                $user,
                $operation,
                __('credits.used_for', ['feature' => __('modules.cover_analysis')]),
                $analysis
            );

            // Log activity
            ActivityLog::log('cover_analysis', $analysis);

            // Return formatted response for frontend
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $analysis->id,
                    'scores' => [
                        'overall' => $overallScore,
                        'readability' => $readabilityScore,
                        'contrast' => $contrastScore,
                        'face_visibility' => $faceVisibilityScore,
                        'emotion' => $emotionScore,
                    ],
                    'ctr_prediction' => $ctrPrediction,
                    'ai_feedback' => nl2br(e($aiFeedback)),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Generate a cover image.
     */
    public function generate(Request $request): JsonResponse
    {
        // Increase PHP execution time for image generation (API can take up to 2-3 minutes)
        set_time_limit(300);

        $request->validate([
            'prompt' => ['required', 'string', 'max:1000'],
            'reference_image' => ['nullable', 'image', 'max:10240'],
            'aspect_ratio' => ['nullable', 'string', 'in:16:9,9:16,1:1,4:3,3:4'],
            'style' => ['nullable', 'string', 'in:modern,gaming,vlog,educational'],
        ]);

        $user = $request->user();
        $operation = 'cover_generation_fal';

        // Check credits
        if (!$this->creditManager->hasEnough($user, $operation)) {
            return response()->json([
                'success' => false,
                'error' => __('credits.insufficient'),
                'required' => $this->creditManager->getCost($operation),
                'available' => $user->credits,
            ], 402);
        }

        try {
            $prompt = $request->prompt;
            $aspectRatio = $request->aspect_ratio ?? '16:9'; // Default: YouTube thumbnail

            $options = [
                'aspect_ratio' => $aspectRatio,
            ];

            // Generate image
            $hasReference = $request->hasFile('reference_image');
            $referencePath = null;

            if ($hasReference) {
                \Log::info('Cover generation with reference image', [
                    'prompt' => $prompt,
                    'aspect_ratio' => $aspectRatio,
                ]);
                $refPath = $request->file('reference_image')->store('temp', 'local');
                $refFullPath = Storage::disk('local')->path($refPath);
                $result = $this->falAI->generateFromReference($prompt, $refFullPath, $options);

                // Store reference image permanently
                $referencePath = $request->file('reference_image')->store(date('Y/m') . '/references', 'covers');

                Storage::disk('local')->delete($refPath);
            } else {
                \Log::info('Cover generation without reference (text to image)', [
                    'prompt' => $prompt,
                    'aspect_ratio' => $aspectRatio,
                ]);
                $result = $this->falAI->generateCover($prompt, $options);
            }

            // Save to database
            $coverGeneration = CoverGeneration::create([
                'user_id' => $user->id,
                'image_path' => $result['path'],
                'original_url' => $result['original_url'] ?? null,
                'prompt' => $prompt,
                'aspect_ratio' => $aspectRatio,
                'has_reference' => $hasReference,
                'reference_path' => $referencePath,
                'model_used' => 'fal-ai/nano-banana-pro',
                'credits_used' => $this->creditManager->getCost($operation),
            ]);

            // Deduct credits
            $this->creditManager->deduct(
                $user,
                $operation,
                __('credits.used_for', ['feature' => __('modules.cover_generation')]),
                $coverGeneration
            );

            // Log activity
            ActivityLog::log('cover_generation', $coverGeneration, ['prompt' => $prompt]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $coverGeneration->id,
                    'path' => $result['path'],
                    'url' => $result['url'],
                    'prompt' => $prompt,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get suggested prompts.
     */
    public function suggestions(Request $request): JsonResponse
    {
        $topic = $request->input('topic', 'YouTube video');
        $style = $request->input('style', 'modern');

        $suggestions = $this->falAI->getSuggestedPrompts($topic, $style);

        return response()->json([
            'success' => true,
            'data' => $suggestions,
        ]);
    }

    /**
     * Fetch image content using cURL.
     */
    protected function fetchImageWithCurl(string $url): ?string
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        // Check if response is valid image
        if ($httpCode === 200 && $content && strpos($contentType, 'image') !== false) {
            // Also check if it's not the default YouTube placeholder (120x90)
            $imageInfo = @getimagesizefromstring($content);
            if ($imageInfo && $imageInfo[0] > 200) { // Width > 200px means it's not placeholder
                return $content;
            }
        }

        return null;
    }

    /**
     * Extract YouTube video ID from various URL formats.
     */
    protected function extractYouTubeVideoId(string $url): ?string
    {
        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/v\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/',
            '/^([a-zA-Z0-9_-]{11})$/', // Just the ID
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Fetch YouTube video thumbnail for preview.
     */
    public function fetchYouTubeThumbnail(Request $request): JsonResponse
    {
        $request->validate([
            'url' => ['required', 'string', 'max:500'],
        ]);

        $videoId = $this->extractYouTubeVideoId($request->url);

        if (!$videoId) {
            return response()->json([
                'success' => false,
                'error' => __('youtube.invalid_url'),
            ], 422);
        }

        // Try maxresdefault first, fallback to hqdefault
        $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";

        // Check if maxres exists (returns 120x90 placeholder if not)
        $headers = @get_headers($thumbnailUrl, true);
        if (!$headers || strpos($headers[0], '404') !== false) {
            $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
        }

        return response()->json([
            'success' => true,
            'data' => [
                'video_id' => $videoId,
                'thumbnail_url' => $thumbnailUrl,
            ],
        ]);
    }
}
