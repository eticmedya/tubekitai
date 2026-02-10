@extends('layouts.dashboard')

@section('title', __('modules.idea_generator'))

@section('content')
<div class="space-y-6" x-data="ideaGenerator()">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                {{ __('modules.idea_generator') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('modules.idea_generator_desc') }}
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95c-.285.475-.507 1-.67 1.55H6a1 1 0 000 2h.013a9.358 9.358 0 000 1H6a1 1 0 100 2h.351c.163.55.385 1.075.67 1.55C7.721 15.216 8.768 16 10 16s2.279-.784 2.979-1.95a1 1 0 10-1.715-1.029c-.472.786-.96.979-1.264.979-.304 0-.792-.193-1.264-.979a4.265 4.265 0 01-.264-.521H10a1 1 0 100-2H8.017a7.36 7.36 0 010-1H10a1 1 0 100-2H8.472c.08-.185.167-.36.264-.521z"/>
                </svg>
                1 {{ __('credits.credit') }}
            </span>
        </div>
    </div>

    <!-- Input Form -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form @submit.prevent="generateIdeas">
            <div class="space-y-4">
                <!-- Generation Mode -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('modules.generation_mode') }}
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label :class="{'ring-2 ring-red-500 bg-red-50 dark:bg-red-900/30': mode === 'topic'}"
                            class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none hover:border-red-300 transition-colors">
                            <input type="radio" value="topic" x-model="mode" class="sr-only">
                            <span class="flex flex-1 flex-col">
                                <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('modules.by_topic') }}</span>
                                <span class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('modules.by_topic_desc') }}</span>
                            </span>
                        </label>
                        <label :class="{'ring-2 ring-red-500 bg-red-50 dark:bg-red-900/30': mode === 'channel'}"
                            class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none hover:border-red-300 transition-colors">
                            <input type="radio" value="channel" x-model="mode" class="sr-only">
                            <span class="flex flex-1 flex-col">
                                <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('modules.by_channel') }}</span>
                                <span class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('modules.by_channel_desc') }}</span>
                            </span>
                        </label>
                        <label :class="{'ring-2 ring-red-500 bg-red-50 dark:bg-red-900/30': mode === 'trending'}"
                            class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none hover:border-red-300 transition-colors">
                            <input type="radio" value="trending" x-model="mode" class="sr-only">
                            <span class="flex flex-1 flex-col">
                                <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('modules.by_trending') }}</span>
                                <span class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('modules.by_trending_desc') }}</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Topic Input -->
                <div x-show="mode === 'topic'">
                    <label for="topic" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('modules.your_topic') }}
                    </label>
                    <input type="text" id="topic" x-model="topic"
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm dark:bg-gray-700 dark:text-white"
                        placeholder="{{ __('modules.topic_placeholder') }}">
                </div>

                <!-- Channel URL Input -->
                <div x-show="mode === 'channel'">
                    <label for="channel_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('modules.channel_url') }}
                    </label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 sm:text-sm">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                        </span>
                        <input type="url" id="channel_url" x-model="channelUrl"
                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500 sm:text-sm"
                            placeholder="https://www.youtube.com/@...">
                    </div>
                </div>

                <!-- Trending Category -->
                <div x-show="mode === 'trending'">
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('modules.category') }}
                    </label>
                    <select id="category" x-model="category"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                        <option value="">{{ __('modules.all_categories') }}</option>
                        <option value="gaming">{{ __('modules.cat_gaming') }}</option>
                        <option value="music">{{ __('modules.cat_music') }}</option>
                        <option value="education">{{ __('modules.cat_education') }}</option>
                        <option value="entertainment">{{ __('modules.cat_entertainment') }}</option>
                        <option value="tech">{{ __('modules.cat_tech') }}</option>
                        <option value="howto">{{ __('modules.cat_howto') }}</option>
                    </select>
                </div>

                <!-- Idea Count -->
                <div>
                    <label for="count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('modules.idea_count') }}
                    </label>
                    <select id="count" x-model="count"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                        <option value="5">5 {{ __('modules.ideas') }}</option>
                        <option value="10" selected>10 {{ __('modules.ideas') }}</option>
                        <option value="15">15 {{ __('modules.ideas') }}</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="submit" :disabled="loading || !isValid()"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <template x-if="loading">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <template x-if="!loading">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </template>
                        <span x-text="loading ? '{{ __('app.generating') }}' : '{{ __('app.generate') }}'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Results -->
    <template x-if="ideas.length > 0">
        <div class="space-y-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('modules.generated_ideas') }}</h3>

            <template x-for="(idea, index) in ideas" :key="index">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <span class="flex items-center justify-center h-10 w-10 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 font-bold" x-text="index + 1"></span>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white" x-text="idea.title"></h4>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300" x-text="idea.description"></p>

                            <!-- Tags -->
                            <div class="mt-3 flex flex-wrap gap-2">
                                <template x-for="tag in idea.tags" :key="tag">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200" x-text="tag"></span>
                                </template>
                            </div>

                            <!-- Additional Info -->
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                <div x-show="idea.target_audience" class="flex items-start">
                                    <svg class="mr-2 h-4 w-4 text-purple-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-400"><strong>{{ __('modules.target_audience') }}:</strong> <span x-text="idea.target_audience"></span></span>
                                </div>
                                <div x-show="idea.video_length" class="flex items-start">
                                    <svg class="mr-2 h-4 w-4 text-blue-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-400"><strong>{{ __('modules.video_length') }}:</strong> <span x-text="idea.video_length"></span></span>
                                </div>
                            </div>

                            <!-- Content Outline -->
                            <div x-show="idea.content_outline && idea.content_outline.length > 0" class="mt-4">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('modules.content_outline') }}:</p>
                                <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                    <template x-for="point in idea.content_outline" :key="point">
                                        <li x-text="point"></li>
                                    </template>
                                </ul>
                            </div>

                            <!-- Stats & Actions -->
                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm">
                                    <span class="flex items-center text-green-600">
                                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="idea.viral_potential + '%'"></span> {{ __('modules.viral_potential') }}
                                    </span>
                                    <span class="flex items-center text-blue-600">
                                        <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span x-text="idea.ctr_prediction + '%'"></span> CTR
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <button @click="copyIdea(idea)" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="{{ __('app.copy') }}">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                    <button @click="saveIdea(idea)" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="{{ __('app.save') }}">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>
</div>

@push('scripts')
<script>
function ideaGenerator() {
    return {
        mode: 'topic',
        topic: '',
        channelUrl: '',
        category: '',
        count: '10',
        loading: false,
        ideas: [],

        isValid() {
            if (this.mode === 'topic') return this.topic.length > 0;
            if (this.mode === 'channel') return this.channelUrl.length > 0;
            return true;
        },

        async generateIdeas() {
            if (!this.isValid()) return;

            this.loading = true;
            this.ideas = [];

            try {
                const response = await fetch('{{ route("idea-generator.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        mode: this.mode,
                        topic: this.topic,
                        channel_url: this.channelUrl,
                        category: this.category,
                        count: parseInt(this.count)
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.ideas = data.data.ideas;
                } else {
                    alert(data.message || '{{ __("app.error_occurred") }}');
                }
            } catch (error) {
                console.error(error);
                alert('{{ __("app.error_occurred") }}');
            } finally {
                this.loading = false;
            }
        },

        copyIdea(idea) {
            navigator.clipboard.writeText(idea.title + '\n\n' + idea.description);
            alert('{{ __("app.copied") }}');
        },

        saveIdea(idea) {
            // Save to local storage or backend
            let saved = JSON.parse(localStorage.getItem('savedIdeas') || '[]');
            saved.push(idea);
            localStorage.setItem('savedIdeas', JSON.stringify(saved));
            alert('{{ __("app.saved") }}');
        }
    }
}
</script>
@endpush
@endsection
