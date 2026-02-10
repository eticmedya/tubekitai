@extends('layouts.dashboard')

@section('title', __('modules.cover_ai'))

@section('content')
<div class="space-y-6" x-data="coverAI()">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                {{ __('modules.cover_ai') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('modules.cover_ai_desc') }}
            </p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex" aria-label="Tabs">
                <button @click="mode = 'analyze'"
                    :class="{'border-red-500 text-red-600': mode === 'analyze', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': mode !== 'analyze'}"
                    class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    {{ __('modules.analyze_cover') }}
                    <span class="ml-2 text-xs text-gray-400">(0.5 {{ __('credits.credit') }})</span>
                </button>
                <button @click="mode = 'generate'"
                    :class="{'border-red-500 text-red-600': mode === 'generate', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': mode !== 'generate'}"
                    class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ __('modules.generate_cover') }}
                    <span class="ml-2 text-xs text-gray-400">(1 {{ __('credits.credit') }})</span>
                </button>
            </nav>
        </div>

        <!-- Analyze Mode -->
        <div x-show="mode === 'analyze'" class="p-6">
            <!-- Source Selection Tabs -->
            <div class="flex space-x-4 mb-6">
                <button type="button" @click="analyzeSource = 'upload'"
                    :class="analyzeSource === 'upload' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="flex-1 py-3 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ __('cover.upload_image') }}
                </button>
                <button type="button" @click="analyzeSource = 'youtube'"
                    :class="analyzeSource === 'youtube' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                    class="flex-1 py-3 px-4 rounded-lg font-medium text-sm transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                    {{ __('cover.youtube_thumbnail') }}
                </button>
            </div>

            <form @submit.prevent="analyzeCover">
                <div class="space-y-4">
                    <!-- Upload Area -->
                    <div x-show="analyzeSource === 'upload'" class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg"
                        @dragover.prevent="dragover = true"
                        @dragleave="dragover = false"
                        @drop.prevent="handleDrop($event)"
                        :class="{'border-red-500 bg-red-50 dark:bg-red-900/20': dragover}">
                        <div class="space-y-1 text-center">
                            <template x-if="!analyzePreview">
                                <div>
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                        <label for="analyze-upload" class="relative cursor-pointer rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none">
                                            <span>{{ __('modules.upload_image') }}</span>
                                            <input id="analyze-upload" name="analyze-upload" type="file" class="sr-only" accept="image/*" @change="handleAnalyzeUpload($event)">
                                        </label>
                                        <p class="pl-1">{{ __('modules.or_drag_drop') }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, WEBP {{ __('modules.up_to') }} 10MB</p>
                                </div>
                            </template>
                            <template x-if="analyzePreview">
                                <div class="relative">
                                    <img :src="analyzePreview" class="max-h-64 rounded-lg mx-auto">
                                    <button type="button" @click="clearAnalyzePreview()"
                                        class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- YouTube URL Input -->
                    <div x-show="analyzeSource === 'youtube'" class="space-y-4">
                        <div>
                            <label for="youtube_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('cover.youtube_url_label') }}
                            </label>
                            <div class="flex space-x-2">
                                <input type="text" id="youtube_url" x-model="youtubeUrl"
                                    placeholder="https://www.youtube.com/watch?v=..."
                                    class="flex-1 px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
                                    :disabled="fetchingThumbnail">
                                <button type="button" @click="fetchYouTubeThumbnail()"
                                    :disabled="!youtubeUrl || fetchingThumbnail"
                                    class="px-4 py-2 bg-gray-800 dark:bg-gray-600 text-white rounded-lg hover:bg-gray-700 dark:hover:bg-gray-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center">
                                    <template x-if="fetchingThumbnail">
                                        <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </template>
                                    {{ __('cover.fetch_thumbnail') }}
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('cover.youtube_url_hint') }}</p>
                        </div>

                        <!-- YouTube Thumbnail Preview -->
                        <template x-if="analyzePreview && analyzeSource === 'youtube'">
                            <div class="relative flex justify-center">
                                <div class="relative">
                                    <img :src="analyzePreview" class="max-h-64 rounded-lg shadow-lg">
                                    <button type="button" @click="clearAnalyzePreview()"
                                        class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <div class="absolute bottom-2 left-2 bg-black/70 text-white text-xs px-2 py-1 rounded">
                                        YouTube Thumbnail
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" :disabled="(!analyzeFile && !youtubeUrl) || loading"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            <template x-if="loading">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            {{ __('app.analyze') }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Analysis Results -->
            <template x-if="analysisResult">
                <div class="mt-6 space-y-6">
                    <!-- Scores -->
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold" :class="getScoreColor(analysisResult.scores.overall)" x-text="analysisResult.scores.overall"></div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.overall_score') }}</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold" :class="getScoreColor(analysisResult.scores.readability)" x-text="analysisResult.scores.readability"></div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.readability') }}</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold" :class="getScoreColor(analysisResult.scores.contrast)" x-text="analysisResult.scores.contrast"></div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.contrast') }}</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold" :class="getScoreColor(analysisResult.scores.face_visibility)" x-text="analysisResult.scores.face_visibility"></div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.face_visibility') }}</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold" :class="getScoreColor(analysisResult.scores.emotion)" x-text="analysisResult.scores.emotion"></div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.emotion') }}</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-red-600" x-text="analysisResult.ctr_prediction + '%'"></div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('modules.ctr_prediction') }}</div>
                        </div>
                    </div>

                    <!-- AI Feedback -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">{{ __('modules.ai_feedback') }}</h4>
                        <div class="prose dark:prose-invert max-w-none text-sm" x-html="analysisResult.ai_feedback"></div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Generate Mode -->
        <div x-show="mode === 'generate'" class="p-6">
            <form @submit.prevent="generateCover">
                <div class="space-y-4">
                    <div>
                        <label for="prompt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('modules.cover_prompt') }}
                        </label>
                        <textarea id="prompt" x-model="generatePrompt" rows="4" required
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('modules.cover_prompt_placeholder') }}"></textarea>
                    </div>

                    <!-- Aspect Ratio Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('cover.aspect_ratio') }}
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="aspectRatio = '16:9'"
                                :class="aspectRatio === '16:9' ? 'bg-red-600 text-white border-red-600' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600'"
                                class="px-4 py-2 border rounded-lg text-sm font-medium transition-colors flex items-center">
                                <svg class="w-5 h-3 mr-2" viewBox="0 0 16 9" fill="currentColor">
                                    <rect width="16" height="9" rx="1"/>
                                </svg>
                                16:9 <span class="ml-1 text-xs opacity-70">({{ __('cover.thumbnail') }})</span>
                            </button>
                            <button type="button" @click="aspectRatio = '9:16'"
                                :class="aspectRatio === '9:16' ? 'bg-red-600 text-white border-red-600' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600'"
                                class="px-4 py-2 border rounded-lg text-sm font-medium transition-colors flex items-center">
                                <svg class="w-3 h-5 mr-2" viewBox="0 0 9 16" fill="currentColor">
                                    <rect width="9" height="16" rx="1"/>
                                </svg>
                                9:16 <span class="ml-1 text-xs opacity-70">({{ __('cover.shorts') }})</span>
                            </button>
                            <button type="button" @click="aspectRatio = '1:1'"
                                :class="aspectRatio === '1:1' ? 'bg-red-600 text-white border-red-600' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600'"
                                class="px-4 py-2 border rounded-lg text-sm font-medium transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" viewBox="0 0 12 12" fill="currentColor">
                                    <rect width="12" height="12" rx="1"/>
                                </svg>
                                1:1 <span class="ml-1 text-xs opacity-70">({{ __('cover.square') }})</span>
                            </button>
                        </div>
                    </div>

                    <!-- Reference Image (Optional) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('modules.reference_image') }} ({{ __('app.optional') }})
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ __('cover.reference_hint') }}</p>
                        <div class="flex items-center space-x-4">
                            <template x-if="!referencePreview">
                                <label class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ __('modules.upload_reference') }}
                                    <input type="file" class="sr-only" accept="image/*" @change="handleReferenceUpload($event)">
                                </label>
                            </template>
                            <template x-if="referencePreview">
                                <div class="relative">
                                    <img :src="referencePreview" class="h-20 rounded-lg">
                                    <button type="button" @click="referencePreview = null; referenceFile = null"
                                        class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" :disabled="!generatePrompt || loading"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            <template x-if="loading">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            {{ __('app.generate') }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Generated Results -->
            <template x-if="generatedCovers.length > 0">
                <div class="mt-6">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-4">{{ __('modules.generated_covers') }}</h4>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <template x-for="cover in generatedCovers" :key="cover.id">
                            <div class="relative group">
                                <img :src="cover.url" class="w-full rounded-lg shadow-lg" :alt="cover.prompt">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center space-x-4">
                                    <a :href="cover.url" download class="p-2 bg-white rounded-full hover:bg-gray-100">
                                        <svg class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Previous Generations History -->
    @if($previousGenerations->count() > 0)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            {{ __('cover.previous_generations') }}
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($previousGenerations as $generation)
            <div class="relative group cursor-pointer" @click="generatedCovers.unshift({ id: {{ $generation->id }}, url: '{{ $generation->image_url }}', prompt: '{{ addslashes($generation->short_prompt) }}' })">
                <img src="{{ $generation->image_url }}" alt="{{ $generation->short_prompt }}" class="w-full h-32 object-cover rounded-lg shadow">
                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex flex-col items-center justify-center p-2">
                    <span class="text-white text-xs text-center line-clamp-2">{{ $generation->short_prompt }}</span>
                    <span class="text-gray-300 text-xs mt-1">{{ $generation->created_at->diffForHumans() }}</span>
                    <div class="flex space-x-2 mt-2">
                        <a href="{{ $generation->image_url }}" download class="p-1.5 bg-white rounded-full hover:bg-gray-100" @click.stop>
                            <svg class="h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </a>
                    </div>
                </div>
                @if($generation->has_reference)
                <span class="absolute top-1 right-1 bg-purple-600 text-white text-xs px-1.5 py-0.5 rounded">REF</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Previous Analyses History -->
    @if($previousAnalyses->count() > 0)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            {{ __('cover.previous_analyses') }}
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($previousAnalyses as $analysis)
            <div class="relative group">
                <img src="{{ $analysis->image_url }}" alt="{{ $analysis->original_filename }}" class="w-full h-32 object-cover rounded-lg shadow">
                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex flex-col items-center justify-center p-2">
                    <span class="text-white text-sm font-bold">{{ $analysis->overall_score }}/100</span>
                    <span class="text-gray-300 text-xs">CTR: {{ $analysis->ctr_prediction }}%</span>
                    <span class="text-gray-300 text-xs mt-1">{{ $analysis->created_at->diffForHumans() }}</span>
                </div>
                <div class="absolute bottom-1 left-1 right-1 bg-gradient-to-t from-black/70 to-transparent rounded-b-lg p-1">
                    <div class="flex items-center justify-between">
                        <span class="text-white text-xs font-semibold">{{ $analysis->overall_score }}</span>
                        <div class="w-12 h-1.5 bg-gray-600 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $analysis->overall_score >= 80 ? 'bg-green-500' : ($analysis->overall_score >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $analysis->overall_score }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function coverAI() {
    return {
        mode: 'analyze',
        loading: false,
        dragover: false,

        // Analyze
        analyzeSource: 'upload',
        analyzeFile: null,
        analyzePreview: null,
        analysisResult: null,
        youtubeUrl: '',
        fetchingThumbnail: false,

        // Generate
        generatePrompt: '',
        aspectRatio: '16:9',
        referenceFile: null,
        referencePreview: null,
        generatedCovers: [],

        handleAnalyzeUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.analyzeFile = file;
                this.analyzePreview = URL.createObjectURL(file);
                this.youtubeUrl = '';
            }
        },

        handleReferenceUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.referenceFile = file;
                this.referencePreview = URL.createObjectURL(file);
            }
        },

        handleDrop(event) {
            this.dragover = false;
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                this.analyzeFile = file;
                this.analyzePreview = URL.createObjectURL(file);
                this.youtubeUrl = '';
            }
        },

        clearAnalyzePreview() {
            this.analyzePreview = null;
            this.analyzeFile = null;
            this.youtubeUrl = '';
            this.analysisResult = null;
        },

        async fetchYouTubeThumbnail() {
            if (!this.youtubeUrl) return;

            this.fetchingThumbnail = true;

            try {
                const response = await fetch('{{ route("cover-ai.fetch-thumbnail") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ url: this.youtubeUrl })
                });

                const data = await response.json();

                if (data.success) {
                    this.analyzePreview = data.data.thumbnail_url;
                    this.analyzeFile = null; // Clear file if URL is used
                } else {
                    alert(data.error || '{{ __("app.error_occurred") }}');
                }
            } catch (error) {
                console.error(error);
                alert('{{ __("app.error_occurred") }}');
            } finally {
                this.fetchingThumbnail = false;
            }
        },

        getScoreColor(score) {
            if (score >= 80) return 'text-green-600';
            if (score >= 60) return 'text-yellow-600';
            return 'text-red-600';
        },

        async analyzeCover() {
            if (!this.analyzeFile && !this.youtubeUrl) return;

            this.loading = true;
            this.analysisResult = null;

            try {
                const formData = new FormData();

                if (this.analyzeSource === 'upload' && this.analyzeFile) {
                    formData.append('image', this.analyzeFile);
                } else if (this.analyzeSource === 'youtube' && this.youtubeUrl) {
                    formData.append('youtube_url', this.youtubeUrl);
                } else {
                    this.loading = false;
                    return;
                }

                const response = await fetch('{{ route("cover-ai.analyze") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    this.analysisResult = data.data;
                } else {
                    alert(data.error || '{{ __("app.error_occurred") }}');
                }
            } catch (error) {
                console.error(error);
                alert('{{ __("app.error_occurred") }}');
            } finally {
                this.loading = false;
            }
        },

        async generateCover() {
            if (!this.generatePrompt) return;

            this.loading = true;

            try {
                const formData = new FormData();
                formData.append('prompt', this.generatePrompt);
                formData.append('aspect_ratio', this.aspectRatio);
                if (this.referenceFile) {
                    formData.append('reference_image', this.referenceFile);
                }

                const response = await fetch('{{ route("cover-ai.generate") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    this.generatedCovers.unshift(data.data);
                } else {
                    alert(data.error || '{{ __("app.error_occurred") }}');
                }
            } catch (error) {
                console.error(error);
                alert('{{ __("app.error_occurred") }}');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush
@endsection
